<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class PaginatedOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'client_id' => 'nullable|integer|exists:clients,id',
            'status' => 'nullable|string|in:pending,in_progress,ready,delivered,cancelled',
            'fecha_desde' => 'nullable|date',
            'fecha_hasta' => 'nullable|date|after_or_equal:fecha_desde',
            'per_page' => 'required|integer|min:1|max:100',
            'page' => 'required|integer|min:1',
        ];
    }
}