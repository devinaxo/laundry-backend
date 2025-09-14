<?php

namespace App\Http\Controllers;
use App\Http\Requests\Order\NewOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subcategory;
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
    public function store(NewOrderRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Generate order number
            $orderNumber = 'PED-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create order
            $order = Order::create([
                'client_id' => $request->validated()['client_id'],
                'order_number' => $orderNumber,
                'reception_date' => $request->validated()['reception_date'],
                'estimated_delivery_date' => $request->validated()['estimated_delivery_date'] ?? null,
                'notes' => $request->validated()['notes'] ?? null,
                'status' => 'pending'
            ]);

            $total = 0;

            // Create order items
            foreach ($request->validated()['items'] as $itemData) {
                $subcategory = Subcategory::find($itemData['subcategory_id']);
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
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        try {
            $order->update($request->validated());
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
