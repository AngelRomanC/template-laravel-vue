<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerfilesRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'guard_name' => 'required',
            'description' => "required",
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'Nombre',
            'guard_name' => 'Guarda',
            'description' => "Descripción",
        ];
    }
}
