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
        $query = Order::with(['cliente', 'items.subcategoria.categoria']);
        
        // Filter by client if provided
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        // Filter by status if provided
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Filter by date range
        if ($request->has('fecha_desde')) {
            $query->where('fecha_recepcion', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta')) {
            $query->where('fecha_recepcion', '<=', $request->fecha_hasta);
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
                'fecha_recepcion' => 'required|date',
                'fecha_entrega_estimada' => 'nullable|date|after:fecha_recepcion',
                'notas' => 'nullable|string|max:1000',
                'items' => 'required|array|min:1',
                'items.*.subcategory_id' => 'required|exists:subcategories,id',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.notas' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            // Generate order number
            $orderNumber = 'PED-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create order
            $order = Order::create([
                'client_id' => $validated['client_id'],
                'numero_pedido' => $orderNumber,
                'fecha_recepcion' => $validated['fecha_recepcion'],
                'fecha_entrega_estimada' => $validated['fecha_entrega_estimada'] ?? null,
                'notas' => $validated['notas'] ?? null,
                'estado' => 'pendiente'
            ]);

            $total = 0;

            // Create order items
            foreach ($validated['items'] as $itemData) {
                $subcategory = \App\Models\Subcategory::find($itemData['subcategory_id']);
                $subtotal = $subcategory->precio * $itemData['cantidad'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'subcategory_id' => $itemData['subcategory_id'],
                    'cantidad' => $itemData['cantidad'],
                    'precio_unitario' => $subcategory->precio,
                    'subtotal' => $subtotal,
                    'notas' => $itemData['notas'] ?? null
                ]);
            }

            // Update order total
            $order->update(['total' => $total]);

            DB::commit();

            $order->load(['cliente', 'items.subcategoria.categoria']);

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
        $order->load(['cliente', 'items.subcategoria.categoria']);
        
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
                'estado' => 'nullable|in:pendiente,en_proceso,listo,entregado,cancelado',
                'fecha_entrega_estimada' => 'nullable|date|after:fecha_recepcion',
                'fecha_entrega_real' => 'nullable|date',
                'notas' => 'nullable|string|max:1000'
            ]);

            $order->update($validated);
            $order->load(['cliente', 'items.subcategoria.categoria']);

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
        if ($order->estado === 'entregado') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un pedido que ya fue entregado'
            ], 422);
        }

        $order->update(['estado' => 'cancelado']);

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
                'estado' => 'required|in:pendiente,en_proceso,listo,entregado,cancelado'
            ]);

            $order->update($validated);

            // If marking as delivered, set delivery date
            if ($validated['estado'] === 'entregado' && !$order->fecha_entrega_real) {
                $order->update(['fecha_entrega_real' => now()->toDateString()]);
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
