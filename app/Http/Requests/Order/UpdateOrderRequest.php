<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'status' => 'nullable|in:pending,in_progress,ready,delivered,cancelled',
            'estimated_delivery_date' => 'nullable|date|after:reception_date',
            'actual_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000'
        ];
    }
}
