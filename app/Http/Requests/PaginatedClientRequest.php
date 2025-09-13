<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatedClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'search' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
            'per_page' => 'required|integer|min:1|max:100',
            'page' => 'required|integer|min:1',
        ];
    }
}
