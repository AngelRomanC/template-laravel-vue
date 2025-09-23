<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreSistemaRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        $isCreate = $this->isMethod('post'); // Detectar si es creación

        return [
            // Sistema fields
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'departamento_id' => 'required|exists:departamentos,id',
            'url' => 'required|url|max:255',
            'fecha_creacion' => 'required|date|before_or_equal:today',
            'fecha_produccion' => 'required|date|after_or_equal:fecha_creacion',
            'estatus' => 'required|string|max:255',
            'numero_usuarios' => 'required|integer|min:0',
            'nombre_servidor' => 'required|string|max:255',
            'ip_servidor' => 'required|string|max:255',
            'sistema_operativo' => 'required|string|max:255',
            'nombre_servidor_bd' => 'required|string|max:255',
            'ip_servidor_bd' => 'required|string|max:255',
            'lenguaje_desarrollo' => 'required|string|max:255',
            'version_lenguaje' => 'required|string|max:255',

            // Archivos de documentos
            'ruta_documento' => 'nullable|array|max:10', // Arreglo de rutas de documentos como máximo 10
            'ruta_documento.*' => 'file|mimes:pdf', // Archivo PDF con máximo 100MB
        ];
    }

    public function messages(): array {
        // Retorna mensajes personalizados para las reglas de validación
        return [
            'fecha_produccion.after_or_equal' => 'La fecha de producción debe ser igual o posterior a la fecha de creación.',
            'ip_servidor.regex' => 'La dirección IP del servidor no tiene un formato válido.',
            'ip_servidor_bd.regex' => 'La dirección IP del servidor de BD no tiene un formato válido.',
        ];
    }

    public function attributes(): array {
        return [
             'departamento_id' => 'departamento', // 
        ];
    }
}