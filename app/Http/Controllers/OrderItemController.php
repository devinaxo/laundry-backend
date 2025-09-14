<?php

namespace App\Http\Controllers;
use App\Http\Requests\Order\NewOrderItemRequest;
use App\Http\Requests\Order\UpdateOrderItemRequest;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Subcategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Order $order): JsonResponse
    {
        $items = $order->items()->with('subcategory.category')->get();
        
        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewOrderItemRequest $request, Order $order): JsonResponse
    {
        try {

            DB::beginTransaction();

            $subcategory = Subcategory::find($request->validated()['subcategory_id']);
            $subtotal = $subcategory->price * $request->validated()['quantity'];

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'subcategory_id' => $request->validated()['subcategory_id'],
                'quantity' => $request->validated()['quantity'],
                'unit_price' => $subcategory->price,
                'subtotal' => $subtotal,
                'notes' => $request->validated()['notes'] ?? null
            ]);

            // Update order total
            $newTotal = $order->items()->sum('subtotal');
            $order->update(['total' => $newTotal]);

            DB::commit();

            $orderItem->load('subcategory.category');

            return response()->json([
                'success' => true,
                'message' => 'Item agregado al pedido exitosamente',
                'data' => $orderItem
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
                'message' => 'Error al agregar item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order, OrderItem $orderItem): JsonResponse
    {
        // Verify the item belongs to the order
        if ($orderItem->order_id !== $order->id) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado en este pedido'
            ], 404);
        }

        $orderItem->load('subcategory.category');
        
        return response()->json([
            'success' => true,
            'data' => $orderItem
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderItemRequest $request, Order $order, OrderItem $orderItem): JsonResponse
    {
        // Verify the item belongs to the order
        if ($orderItem->order_id !== $order->id) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado en este pedido'
            ], 404);
        }

        try {

            DB::beginTransaction();

            $subcategory = Subcategory::find($request->validated()['subcategory_id']);
            $subtotal = $subcategory->price * $request->validated()['quantity'];

            $orderItem->update([
                'subcategory_id' => $request->validated()['subcategory_id'],
                'quantity' => $request->validated()['quantity'],
                'unit_price' => $subcategory->price,
                'subtotal' => $subtotal,
                'notes' => $request->validated()['notes'] ?? null
            ]);

            // Update order total
            $newTotal = $order->items()->sum('subtotal');
            $order->update(['total' => $newTotal]);

            DB::commit();

            $orderItem->load('subcategory.category');

            return response()->json([
                'success' => true,
                'message' => 'Item actualizado exitosamente',
                'data' => $orderItem
            ]);

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
                'message' => 'Error al actualizar item: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, OrderItem $orderItem): JsonResponse
    {
        // Verify the item belongs to the order
        if ($orderItem->order_id !== $order->id) {
            return response()->json([
                'success' => false,
                'message' => 'Item no encontrado en este pedido'
            ], 404);
        }

        // Don't allow deleting items if order is delivered
        if ($order->status === 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar items de un pedido entregado'
            ], 422);
        }

        DB::beginTransaction();

        $orderItem->delete();

        // Update order total
        $newTotal = $order->items()->sum('subtotal');
        $order->update(['total' => $newTotal]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Item eliminado exitosamente'
        ]);
    }
}
