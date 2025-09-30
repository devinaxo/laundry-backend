<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class ReplaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'reception_date' => 'required|date',
            'estimated_delivery_date' => 'nullable|date|after:reception_date',
            'status' => 'required|in:pending,in_progress,ready,delivered,cancelled',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.subcategory_id' => 'required|exists:subcategories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500'
        ];
    }
}