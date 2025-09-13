<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
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

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'El cliente es obligatorio',
            'client_id.exists' => 'El cliente seleccionado no existe',
            'reception_date.required' => 'La fecha de recepción es obligatoria',
            'reception_date.date' => 'La fecha de recepción debe ser una fecha válida',
            'estimated_delivery_date.after' => 'La fecha de entrega debe ser posterior a la fecha de recepción',
            'items.required' => 'Debe agregar al menos un item al pedido',
            'items.min' => 'Debe agregar al menos un item al pedido',
            'items.*.subcategory_id.required' => 'La subcategoría es obligatoria para cada item',
            'items.*.subcategory_id.exists' => 'La subcategoría seleccionada no existe',
            'items.*.quantity.required' => 'La cantidad es obligatoria para cada item',
            'items.*.quantity.min' => 'La cantidad debe ser al menos 1',
            'items.*.quantity.integer' => 'La cantidad debe ser un número entero'
        ];
    }
}
