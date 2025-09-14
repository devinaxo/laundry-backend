<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class NewOrderRequest extends FormRequest {
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
            'client_id' => 'required|exists:clients,id',
            'reception_date' => 'required|date',
            'estimated_delivery_date' => 'nullable|date|after:reception_date',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.subcategory_id' => 'required|exists:subcategories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:500'
        ];
    }
}
