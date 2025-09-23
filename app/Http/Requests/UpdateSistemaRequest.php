<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSistemaRequest extends FormRequest {
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
        return [
            // Sistema fields
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000', // Increased max length for description
            'departamento_id' => 'required|exists:departamentos,id',
            'url' => 'required|url|max:255', // Changed to url validation
            'fecha_creacion' => 'required|date|before_or_equal:today', // Ensure date is not in future
            'fecha_produccion' => 'required|date|after_or_equal:fecha_creacion', // Must be after creation date
            'estatus' => 'required|string|max:255',
            'numero_usuarios' => 'required|integer|min:0', // Changed to integer validation
            'nombre_servidor' => 'required|string|max:255',
            'ip_servidor' => 'required|string|max:255', // Basic IP validation
            'sistema_operativo' => 'required|string|max:255',
            'nombre_servidor_bd' => 'required|string|max:255',
            'ip_servidor_bd' => 'required|string|max:255',
            'lenguaje_desarrollo' => 'required|string|max:255',
            'version_lenguaje' => 'required|string|max:255',
            
            // Archivos de documentos 
            'nuevos_documentos_principales' => 'nullable|array|max:10', // Arreglo de rutas de documentos como máximo 10
            'nuevos_documentos_principales.*' => 'file|mimes:pdf', // Archivo PDF con máximo 10MB
            'archivos_a_eliminar' => 'nullable|array', // Arreglo de IDs de documentos a eliminar
            'archivos_a_eliminar.*' => 'integer|exists:documento_sistemas,id', // IDs de documentos existentes
        ];
    }

    public function messages(): array  {
        // Retorna mensajes personalizados para las reglas de validación
        return [
            'fecha_produccion.after_or_equal' => 'La fecha de producción debe ser igual o posterior a la fecha de creación.',
            'ip_servidor.regex' => 'La dirección IP del servidor no tiene un formato válido.',
            'ip_servidor_bd.regex' => 'La dirección IP del servidor de BD no tiene un formato válido.',
            'nuevos_documentos_principales.max' => 'No se pueden subir más de 10 archivos a la vez.',
            'nuevos_documentos_principales.*.max' => 'El archivo :attribute no debe ser mayor a 10MB.',
            'nuevos_documentos_principales.*.mimes' => 'El archivo :attribute debe ser un PDF.',
        ];
    }

    public function attributes(): array {
        return [
            'departamento_id' => 'departamento',
            'nuevos_documentos_principales.*' => 'documento PDF',
            'archivos_a_eliminar.*' => 'documento a eliminar',
        ];
    }
}