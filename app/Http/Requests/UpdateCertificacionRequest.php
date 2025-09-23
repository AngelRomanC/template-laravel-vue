<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificacionRequest extends FormRequest {
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
            'descripcion' => 'required|string|max:1000',
            'departamento_id' => 'required|exists:departamentos,id',
            'fecha_solicitud' => 'required|date', 
            'fecha_entrega' => 'required|date|after_or_equal:fecha_solicitud', 
            'fecha_inicio_vigencia' => 'required|date', 
            'fecha_fin_vigencia' => 'required|date',
            'estatus' => 'required|string|max:255', 
            'numero_usuarios' => 'required|integer|min:0',
            'nombre_responsable' => 'required|string|max:255', 
            'nombre_autorizacion' => 'required|string|max:255',
            
            // Archivos de documentos 
            'nuevos_documentos_principales' => 'nullable|array|max:10', // Arreglo de rutas de documentos como máximo 10
            'nuevos_documentos_principales.*' => 'file|mimes:pdf', // Archivo PDF con máximo 10MB
            'archivos_a_eliminar' => 'nullable|array', // Arreglo de IDs de documentos a eliminar
            'archivos_a_eliminar.*' => 'integer|exists:documento_certificacions,id', // IDs de documentos existentes
        ];
    }

    public function messages(): array  {
        // Retorna mensajes personalizados para las reglas de validación
        return [
            'fecha_entrega.after_or_equal' => 'La fecha de entrega debe ser igual o posterior a la fecha de creación.',
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