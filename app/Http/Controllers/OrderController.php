<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\NewOrderRequest;
use App\Http\Requests\Order\PaginatedOrderRequest;
use App\Http\Requests\Order\ReplaceOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse {
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
     * Get paginated orders with filtering
     */
    public function paginated(PaginatedOrderRequest $request): JsonResponse {
        $query = Order::with(['client', 'items.subcategory.category']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('forename', 'like', "%{$search}%")
                            ->orWhere('surname', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->input('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->input('fecha_desde')) {
            $query->where('reception_date', '>=', $request->input('fecha_desde'));
        }

        if ($request->input('fecha_hasta')) {
            $query->where('reception_date', '<=', $request->input('fecha_hasta'));
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        return response()->json($orders);
    }

    /**
     * Get the 5 most recent orders
     */
    public function recent(): JsonResponse
    {
        $recentOrders = Order::with(['client', 'items.subcategory.category'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $recentOrders
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewOrderRequest $request): JsonResponse {
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
    public function show(Order $order): JsonResponse {
        $order->load(['client', 'items.subcategory.category']);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order): JsonResponse {
        try {
            $validated = $request->validated();
            $originalStatus = $order->status;
            
            $order->update($validated);
            
            if ($originalStatus === 'delivered' && isset($validated['status']) && $validated['status'] !== 'delivered') {
                $order->update(['actual_delivery_date' => null]);
            }
            elseif (isset($validated['status']) && $validated['status'] === 'delivered' && !$order->actual_delivery_date) {
                $order->update(['actual_delivery_date' => now()->toDateString()]);
            }
            
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
     * Replace the entire order with new data (order details + items)
     */
    public function replace(ReplaceOrderRequest $request, Order $order): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();
            $originalStatus = $order->status;
            $newStatus = $validated['status'];

            $order->update([
                'client_id' => $validated['client_id'],
                'reception_date' => $validated['reception_date'],
                'estimated_delivery_date' => $validated['estimated_delivery_date'] ?? null,
                'actual_delivery_date' => $validated['actual_delivery_date'] ?? null,
                'status' => $newStatus,
                'notes' => $validated['notes'] ?? null,
            ]);

            if ($originalStatus === 'delivered' && $newStatus !== 'delivered' && $order->actual_delivery_date) {
                $order->update(['actual_delivery_date' => null]);
                error_log('✅ Cleared actual_delivery_date - status changed from delivered to ' . $newStatus);
            }
            elseif ($newStatus === 'delivered' && !$order->actual_delivery_date && !isset($validated['actual_delivery_date'])) {
                $order->update(['actual_delivery_date' => now()->toDateString()]);
                error_log('✅ Set actual_delivery_date - status changed to delivered');
            }

            $order->items()->delete();

            $total = 0;

            foreach ($validated['items'] as $itemData) {
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

            $order->update(['total' => $total]);

            DB::commit();

            $order->load(['client', 'items.subcategory.category']);

            return response()->json([
                'success' => true,
                'message' => 'Pedido reemplazado exitosamente',
                'data' => $order
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
                'message' => 'Error al reemplazar el pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): JsonResponse {
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
    public function updateStatus(Request $request, Order $order): JsonResponse {
        try {
            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,ready,delivered,cancelled'
            ]);
            
            $originalStatus = $order->status; // Store original status before update
            $order->update($validated);

            error_log("=== Order Status Update Debug ===");
            error_log("Validated data: " . json_encode($validated));
            error_log("Original status: " . $originalStatus);
            error_log("New status: " . $validated['status']);
            error_log("===============================");

            // If status changed from delivered to something else, clear delivery date
            if ($originalStatus === 'delivered' && $validated['status'] !== 'delivered') {
                $order->update(['actual_delivery_date' => null]);
                error_log('✅ Cleared actual_delivery_date - status changed from delivered');
            }
            // If marking as delivered, set delivery date
            elseif ($validated['status'] === 'delivered' && !$order->actual_delivery_date) {
                $order->update(['actual_delivery_date' => now()->toDateString()]);
                error_log('✅ Set actual_delivery_date - status changed to delivered');
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

    /**
     * Upload payment proof file for an order
     */
    public function uploadPaymentProof(Request $request, Order $order): JsonResponse
    {
        try {
            $request->validate([
                'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
                'payment_type' => 'required|in:cash,transfer'
            ]);

            // Validate that payment type is transfer
            if ($request->payment_type !== 'transfer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden subir comprobantes para pagos por transferencia'
                ], 422);
            }

            // Delete old file if exists
            if ($order->payment_proof_path) {
                try {
                    Storage::disk('payment_proofs')->delete($order->payment_proof_path);
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete old payment proof: ' . $e->getMessage());
                }
            }

            // Generate unique filename
            $file = $request->file('payment_proof');
            $extension = $file->getClientOriginalExtension();
            $filename = 'payment_proof_' . $order->order_number . '_' . time() . '.' . $extension;
            
            // Upload file to FTPS
            $contents = file_get_contents($file->getRealPath());
            $uploaded = Storage::disk('payment_proofs')->put($filename, $contents);

            if (!$uploaded) {
                throw new \Exception('Failed to upload file to FTP server');
            }

            // Update order
            $order->update([
                'payment_type' => $request->payment_type,
                'payment_proof_path' => $filename
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comprobante de pago subido exitosamente',
                'data' => [
                    'payment_proof_path' => $filename,
                    'payment_type' => $order->payment_type
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('FTPS upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get/Download payment proof file for an order
     */
    public function getPaymentProof(Order $order): JsonResponse|\Symfony\Component\HttpFoundation\StreamedResponse
    {
        try {
            if (!$order->payment_proof_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este pedido no tiene comprobante de pago'
                ], 404);
            }

            if (!Storage::disk('payment_proofs')->exists($order->payment_proof_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El comprobante de pago no se encuentra en el servidor'
                ], 404);
            }

            // Get the file from FTPS
            $file = Storage::disk('payment_proofs')->get($order->payment_proof_path);
            $mimeType = Storage::disk('payment_proofs')->mimeType($order->payment_proof_path);
            $filename = basename($order->payment_proof_path);

            // Return file as download
            return response()->streamDownload(function () use ($file) {
                echo $file;
            }, $filename, [
                'Content-Type' => $mimeType,
            ]);

        } catch (\Exception $e) {
            \Log::error('FTPS download failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el comprobante: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete payment proof file for an order
     */
    public function deletePaymentProof(Order $order): JsonResponse
    {
        try {
            if (!$order->payment_proof_path) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este pedido no tiene comprobante de pago'
                ], 404);
            }

            // Delete file from FTPS
            if (Storage::disk('payment_proofs')->exists($order->payment_proof_path)) {
                Storage::disk('payment_proofs')->delete($order->payment_proof_path);
            }

            // Clear payment proof path
            $order->update([
                'payment_proof_path' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comprobante de pago eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('FTPS delete failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el comprobante: ' . $e->getMessage()
            ], 500);
        }
    }
}
