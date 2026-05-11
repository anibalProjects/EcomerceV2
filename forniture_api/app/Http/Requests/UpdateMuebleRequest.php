<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMuebleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
        'nombre' => 'sometimes|required|string|max:255',
        'descripcion' => 'nullable|string',
        'precio' => 'sometimes|required|numeric|min:0',
        'categoria_id' => 'sometimes|required|exists:categories,id',
        'stock' => 'sometimes|required|integer|min:0',
        'color' => 'nullable|string|max:50',
        'novedad' => 'boolean',
        'activo' => 'boolean',
    ];
    }
}
