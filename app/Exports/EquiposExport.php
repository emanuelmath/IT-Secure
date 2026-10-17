<?php

namespace App\Exports;

use App\Models\Equipo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EquiposExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function query()
    {
        return Equipo::query()->with([
            'centroDeComputo.sede.empresa',
            'registrador'
        ]);
    }

    public function map($equipo): array
    {
        return [
            $equipo->codigo_inventario,
            ucfirst($equipo->tipo),
            $equipo->marca ?? 'N/A',
            $equipo->modelo ?? 'N/A',
            $equipo->numero_serie ?? 'N/A',
            ucfirst(str_replace('_', ' ', $equipo->estado)), // Convierte 'baja_definitiva' en 'Baja definitiva'
            $equipo->centroDeComputo->nombre ?? 'No asignado',
            $equipo->centroDeComputo->sede->nombre ?? 'N/A',
            $equipo->centroDeComputo->sede->empresa->nombre ?? 'N/A',
            $equipo->dias_frecuencia_mantenimiento,
            optional($equipo->fecha_ultimo_mantenimiento)->format('d/m/Y') ?? 'Sin registro',
            $equipo->registrador->nombre ?? 'Sistema',
            optional($equipo->created_at)->format('d/m/Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'CÓDIGO INVENTARIO',
            'TIPO',
            'MARCA',
            'MODELO',
            'N° SERIE',
            'ESTADO',
            'CENTRO DE CÓMPUTO',
            'SEDE',
            'EMPRESA',
            'FRECUENCIA MANT. (DÍAS)',
            'ÚLTIMO MANTENIMIENTO',
            'REGISTRADO POR',
            'FECHA REGISTRO',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0C1220']
                ],
            ],
        ];
    }
}
