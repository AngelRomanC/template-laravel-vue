<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreCertificacionRequest extends FormRequest {
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
            'fecha_solicitud' => 'required|date',
            'fecha_entrega' => 'required|date|after_or_equal:fecha_solicitud',
            'fecha_inicio_vigencia' => 'required|date',
            'fecha_fin_vigencia' => 'required|date',
            'estatus' => 'required|string|max:255',
            'numero_usuarios' => 'required|integer|min:0',
            'nombre_responsable' => 'required|string|max:255',
            'nombre_autorizacion' => 'required|string|max:255',

            // Archivos de documentos
            'ruta_documento' => 'nullable|array|max:10', // Arreglo de rutas de documentos como máximo 10
            'ruta_documento.*' => 'file|mimes:pdf', // Archivo PDF con máximo 100MB
        ];
    }

    public function messages(): array {
        // Retorna mensajes personalizados para las reglas de validación
        return [
            'fecha_entrega.after_or_equal' => 'La fecha de entrega debe ser igual o posterior a la fecha de creación.',
        ];
    }

    public function attributes(): array {
        return [
             'departamento_id' => 'departamento', // 
        ];
    }
}
