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
            'fecha_recepcion' => 'required|date',
            'fecha_entrega_estimada' => 'nullable|date|after:fecha_recepcion',
            'notas' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.subcategory_id' => 'required|exists:subcategories,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.notas' => 'nullable|string|max:500'
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
            'fecha_recepcion.required' => 'La fecha de recepción es obligatoria',
            'fecha_recepcion.date' => 'La fecha de recepción debe ser una fecha válida',
            'fecha_entrega_estimada.after' => 'La fecha de entrega debe ser posterior a la fecha de recepción',
            'items.required' => 'Debe agregar al menos un item al pedido',
            'items.min' => 'Debe agregar al menos un item al pedido',
            'items.*.subcategory_id.required' => 'La subcategoría es obligatoria para cada item',
            'items.*.subcategory_id.exists' => 'La subcategoría seleccionada no existe',
            'items.*.cantidad.required' => 'La cantidad es obligatoria para cada item',
            'items.*.cantidad.min' => 'La cantidad debe ser al menos 1',
            'items.*.cantidad.integer' => 'La cantidad debe ser un número entero'
        ];
    }
}
