<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['client', 'items.subcategory.category']);
        
        // Filter by client if provided
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        // Filter by status if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('fecha_desde')) {
            $query->where('reception_date', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta')) {
            $query->where('reception_date', '<=', $request->fecha_hasta);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'reception_date' => 'required|date',
                'estimated_delivery_date' => 'nullable|date|after:reception_date',
                'notes' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.subcategory_id' => 'required|exists:subcategories,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.notes' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            // Generate order number
            $orderNumber = 'PED-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create order
            $order = Order::create([
                'client_id' => $validated['client_id'],
                'order_number' => $orderNumber,
                'reception_date' => $validated['reception_date'],
                'estimated_delivery_date' => $validated['estimated_delivery_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending'
            ]);

            $total = 0;

            // Create order items
            foreach ($validated['items'] as $itemData) {
                $subcategory = \App\Models\Subcategory::find($itemData['subcategory_id']);
                $subtotal = $subcategory->price * $itemData['quantity'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'subcategory_id' => $itemData['subcategory_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $subcategory->price,
                    'subtotal' => $subtotal,
                    'notes' => $itemData['notes'] ?? null
                ]);
            }

            // Update order total
            $order->update(['total' => $total]);

            DB::commit();

            $order->load(['client', 'items.subcategory.category']);

            return response()->json([
                'success' => true,
                'message' => 'Pedido creado exitosamente',
                'data' => $order
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['client', 'items.subcategory.category']);
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'nullable|in:pending,in_progress,ready,delivered,cancelled',
                'estimated_delivery_date' => 'nullable|date|after:reception_date',
                'actual_delivery_date' => 'nullable|date',
                'notes' => 'nullable|string|max:1000'
            ]);

            $order->update($validated);
            $order->load(['client', 'items.subcategory.category']);

            return response()->json([
                'success' => true,
                'message' => 'Pedido actualizado exitosamente',
                'data' => $order
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse
    {
        if ($order->status === 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un pedido que ya fue entregado'
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Pedido cancelado exitosamente'
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,ready,delivered,cancelled'
            ]);

            $order->update($validated);

            // If marking as delivered, set delivery date
            if ($validated['status'] === 'delivered' && !$order->actual_delivery_date) {
                $order->update(['actual_delivery_date' => now()->toDateString()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Estado del pedido actualizado exitosamente',
                'data' => $order
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
