<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'numero' => ['required', 'digits:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required','string', 'min:8'],
            'roles' => ['required', 'array'],
            'roles.*' => ['integer', 'exists:roles,id'],  
        ];   
        return $rules;
        
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
            'apellido_materno' => 'El campo apellido materno es obligatorio.',
            'numero' => 'El campo Teléfono es obligatorio.',
            'numero.digits' => 'El número de teléfono debe contener exactamente 10 dígitos.',
            'email' => 'El campo Correo Electronico es obligatorio.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password' => 'El campo contraseña  es obligatorio.',
            'password.min' => 'Debe tener al menos 8 caracteres',


            'roles.required' => 'Debes asignar al menos un rol al usuario.',
            'roles.array' => 'El formato de roles no es válido.',
            'roles.*.exists' => 'Alguno de los roles seleccionados no existe en el sistema.',
            'departamento_id.required' => 'El campo departamento es obligatorio cuando el rol es Ejecutivo.',

        ];
    }
}
