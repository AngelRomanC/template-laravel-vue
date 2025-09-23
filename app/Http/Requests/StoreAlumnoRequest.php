<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required'],
            'cuatrimestre'=> ['required', 'string', 'max:255'],
            'matricula'=> ['required', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio.',
            'apellido_materno' => 'El campo apellido materno es obligatorio.',
            'numero' => 'El campo Teléfono es obligatorio.',
            'email' => 'El campo Correo Electronico es obligatorio.',
            'password' => 'El campo contraseña  es obligatorio.',
            'cuatrimestre' => 'El campo Cuatrimestre es obligatorio.',
            'matricula' => 'El campo Matricula  es obligatorio.',
        ];
    }
}
