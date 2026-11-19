<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertarMantenimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol(['Administrador', 'Supervisor', 'Técnico de Soporte']);
    }

    public function rules(): array
    {
        return [
            'equipo_id' => ['required', 'integer', 'exists:equipos,id'],
            'tipo' => ['required', 'in:preventivo,correctivo'],
            'fecha_mantenimiento' => ['required', 'date'],
            'descripcion_trabajo' => ['required', 'string'],
            'piezas_reemplazadas' => ['nullable', 'string'],
            'estado_equipo' => ['required', 'in:operativo,mantenimiento,averiado'],
        ];
    }

    public function messages(): array
    {
        return [
            'equipo_id.required' => 'Debe seleccionar un equipo a intervenir.',
            'equipo_id.exists' => 'El equipo seleccionado no existe o no tiene permisos sobre él.',
            'tipo.required' => 'Debe seleccionar el tipo de mantenimiento (preventivo o correctivo).',
            'tipo.in' => 'El tipo de mantenimiento seleccionado no es válido.',
            'fecha_mantenimiento.required' => 'La fecha del mantenimiento es obligatoria.',
            'fecha_mantenimiento.date' => 'La fecha del mantenimiento debe ser una fecha válida.',
            'descripcion_trabajo.required' => 'Debe detallar el trabajo o servicio realizado.',
            'estado_equipo.required' => 'Debe definir el estado en que queda el equipo tras el mantenimiento.',
            'estado_equipo.in' => 'El estado del equipo debe ser operativo, mantenimiento o averiado.',
        ];
    }
}
