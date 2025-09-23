<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->id)
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'roles' => ['required', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],
        ];
        // Validación específica si el rol seleccionado es Ejecutivo (opcional)
        // if ($this->rol == ID_DEL_ROL_EJECUTIVO) {
        //     $rules['departamento_id'] = 'required|exists:departamentos,id';
        // }
        return $rules;
    }
    public function attributes(): array
    {
        return [
            'name' => 'Nombre',
            'apellido_paterno' => 'Apellido Paterno',
            'apellido_materno' => 'Apellido Materno',
            'numero' => 'Número Telefónico',
            'email' => 'Correo Electrónico',
            'roles' => 'Roles',
        ];
    }

}
