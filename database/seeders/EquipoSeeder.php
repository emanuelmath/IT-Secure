<?php

namespace Database\Seeders;

use App\Models\CentroComputo;
use App\Models\Equipo;
use App\Models\User;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Seeder;
use function PHPUnit\Framework\isEmpty;

class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        if (blank($adminEmail)) {
            throw new InvalidArgumentException('No se puede crear el seeder porque falta ADMIN_EMAIL en el .env');
        }
        $admin      = User::where('email', $adminEmail)->firstOrFail();
        $ccSS01 = CentroComputo::where('codigo', 'CC-SS-01')->firstOrFail();
        $ccSS02 = CentroComputo::where('codigo', 'CC-SS-02')->firstOrFail();
        $ccSA01 = CentroComputo::where('codigo', 'CC-SA-01')->firstOrFail();
        $ccSA02 = CentroComputo::where('codigo', 'CC-SA-02')->firstOrFail();
        $nocEsc = CentroComputo::where('codigo', 'NOC-ESC-01')->firstOrFail();

        $equipos = [
            [
                'centros_de_computo_id'         => $ccSS01->id,
                'codigo_inventario'             => 'INV-SS01-PC01',
                'tipo'                          => 'desktop',
                'marca'                         => 'Dell',
                'modelo'                        => 'OptiPlex 7010',
                'numero_serie'                  => 'DL-98234-OPT',
                'estado'                        => 'operativo',
                'dias_frecuencia_mantenimiento' => 180,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subDays(30)->toDateString(),
                'registrado_por'                => $admin?->id,
            ],
            [
                'centros_de_computo_id'         => $ccSS01->id,
                'codigo_inventario'             => 'INV-SS01-SW01',
                'tipo'                          => 'switch',
                'marca'                         => 'Cisco',
                'modelo'                        => 'Catalyst 2960-X',
                'numero_serie'                  => 'FCW2145L09K',
                'estado'                        => 'operativo',
                'dias_frecuencia_mantenimiento' => 90,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subDays(86)->toDateString(),
                'registrado_por'                => $admin?->id,
            ],
            [
                'centros_de_computo_id'         => $ccSS02->id,
                'codigo_inventario'             => 'INV-SS02-LP01',
                'tipo'                          => 'laptop',
                'marca'                         => 'Lenovo',
                'modelo'                        => 'ThinkPad T14 Gen 4',
                'numero_serie'                  => 'PF-4X99201',
                'estado'                        => 'operativo',
                'dias_frecuencia_mantenimiento' => 120,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subDays(135)->toDateString(),
                'registrado_por'                => $admin?->id,
            ],
            [
                'centros_de_computo_id'         => $ccSA01->id,
                'codigo_inventario'             => 'INV-SA01-PR01',
                'tipo'                          => 'impresora',
                'marca'                         => 'HP',
                'modelo'                        => 'LaserJet Enterprise M507',
                'numero_serie'                  => 'PHBCR78210',
                'estado'                        => 'mantenimiento',
                'dias_frecuencia_mantenimiento' => 90,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subDays(92)->toDateString(),
                'registrado_por'                => $admin?->id,
            ],
            [
                'centros_de_computo_id'         => $ccSA01->id,
                'codigo_inventario'             => 'INV-SA01-PC05',
                'tipo'                          => 'desktop',
                'marca'                         => 'HP',
                'modelo'                        => 'ProDesk 400 G6',
                'numero_serie'                  => 'MXL0192834',
                'estado'                        => 'averiado',
                'dias_frecuencia_mantenimiento' => 180,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subDays(20)->toDateString(),
                'registrado_por'                => $admin?->id,
            ],
            // Servidor Crítico NOC
            [
                'centros_de_computo_id'         => $nocEsc->id,
                'codigo_inventario'             => 'INV-NOC-SRV01',
                'tipo'                          => 'servidor',
                'marca'                         => 'Dell',
                'modelo'                        => 'PowerEdge R750',
                'numero_serie'                  => 'SRV-88412-PE',
                'estado'                        => 'operativo',
                'dias_frecuencia_mantenimiento' => 60,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subDays(15)->toDateString(),
                'registrado_por'                => $admin?->id,
            ],
            // Router Core Santa Ana
            [
                'centros_de_computo_id'         => $ccSA02->id,
                'codigo_inventario'             => 'INV-SA02-RT01',
                'tipo'                          => 'router',
                'marca'                         => 'MikroTik',
                'modelo'                        => 'CCR2004-1G-12S+2XS',
                'numero_serie'                  => 'HE809123881',
                'estado'                        => 'operativo',
                'dias_frecuencia_mantenimiento' => 180,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subDays(45)->toDateString(),
                'registrado_por'                => $admin?->id,
            ],
            // Equipo dado de baja definitiva
            [
                'centros_de_computo_id'         => $ccSS02->id,
                'codigo_inventario'             => 'INV-SS02-PC99',
                'tipo'                          => 'desktop',
                'marca'                         => 'Acer',
                'modelo'                        => 'Veriton X2640G',
                'numero_serie'                  => 'UXVMS00192',
                'estado'                        => 'baja_definitiva',
                'dias_frecuencia_mantenimiento' => 180,
                'fecha_ultimo_mantenimiento'    => Carbon::now()->subYear()->toDateString(),
                'registrado_por'                => $admin?->id,
                'eliminado_por'                 => $admin?->id,
            ],
        ];

        foreach ($equipos as $equipo) {
            Equipo::updateOrCreate(
                ['codigo_inventario' => $equipo['codigo_inventario']],
                $equipo
            );
        }
    }
}
