<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'nombre' => 'required|string|max:255|unique:categories',
            'descripcion' => 'nullable|string|max:500',
            'activa' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la categoría es obligatorio',
            'nombre.unique' => 'Ya existe una categoría con este nombre',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'descripcion.max' => 'La descripción no puede tener más de 500 caracteres'
        ];
    }
}
