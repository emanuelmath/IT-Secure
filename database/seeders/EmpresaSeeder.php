<?php

namespace Database\Seeders;

use App\Models\CentroComputo;
use App\Models\Empresa;
use App\Models\Sede;
use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        $uma = Empresa::firstOrCreate(
            ['nit' => '0614-150895-101-2'],
            ['nombre' => 'Universidad Modular Abierta']
        );

        $techCorp = Empresa::firstOrCreate(
            ['nit' => '0614-220410-104-8'],
            ['nombre' => 'Grupo Tecnológico Salvadoreño S.A. de C.V.']
        );

        $sedeSanSalvador = Sede::firstOrCreate(
            ['empresa_id' => $uma->id, 'nombre' => 'Campus Central San Salvador']
        );

        $sedeSantaAna = Sede::firstOrCreate(
            ['empresa_id' => $uma->id, 'nombre' => 'Centro Regional Santa Ana']
        );

        $sedeCorporativa = Sede::firstOrCreate(
            ['empresa_id' => $techCorp->id, 'nombre' => 'Torre Operativa Escalón']
        );

        $centros = [
            [
                'sede_id' => $sedeSanSalvador->id,
                'codigo'  => 'CC-SS-01',
                'nombre'  => 'Laboratorio de Redes y Ciberseguridad',
            ],
            [
                'sede_id' => $sedeSanSalvador->id,
                'codigo'  => 'CC-SS-02',
                'nombre'  => 'Centro de Desarrollo de Software',
            ],
            [
                'sede_id' => $sedeSantaAna->id,
                'codigo'  => 'CC-SA-01',
                'nombre'  => 'Laboratorio de Cómputo General A',
            ],
            [
                'sede_id' => $sedeSantaAna->id,
                'codigo'  => 'CC-SA-02',
                'nombre'  => 'Sala de Servidores y Telecomunicaciones',
            ],
            [
                'sede_id' => $sedeCorporativa->id,
                'codigo'  => 'NOC-ESC-01',
                'nombre'  => 'Centro de Operaciones de Red (NOC)',
            ],
        ];

        foreach ($centros as $centro) {
            CentroComputo::firstOrCreate(
                ['codigo' => $centro['codigo']],
                [
                    'sede_id' => $centro['sede_id'],
                    'nombre'  => $centro['nombre'],
                ]
            );
        }
    }
}
