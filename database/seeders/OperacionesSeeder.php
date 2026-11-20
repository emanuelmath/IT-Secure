<?php

namespace Database\Seeders;

use App\Models\Bitacora;
use App\Models\CentroComputo;
use App\Models\Equipo;
use App\Models\HistorialTraslado;
use App\Models\Mantenimiento;
use App\Models\User;
use Carbon\Carbon;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Seeder;
use function PHPUnit\Framework\isEmpty;

class OperacionesSeeder extends Seeder
{
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL');
        if (blank($adminEmail)) {
            throw new InvalidArgumentException('No se puede crear el seeder porque falta ADMIN_EMAIL en el .env');
        }
        $admin      = User::where('email', $adminEmail)->firstOrFail();
        $supervisor = User::where('email', 'supervisor.ss@itsecure.sv')->firstOrFail();
        $tecnico    = User::where('email', 'tecnico.sa@itsecure.sv')->firstOrFail();

        $pcOperativa  = Equipo::where('codigo_inventario', 'INV-SS01-PC01')->firstOrFail();
        $switchCisco  = Equipo::where('codigo_inventario', 'INV-SS01-SW01')->firstOrFail();
        $pcReincidente = Equipo::where('codigo_inventario', 'INV-SA01-PC05')->firstOrFail();
        $servidorNoc  = Equipo::where('codigo_inventario', 'INV-NOC-SRV01')->firstOrFail();

        $ccSS01 = CentroComputo::where('codigo', 'CC-SS-01')->firstOrFail();
        $ccSS02 = CentroComputo::where('codigo', 'CC-SS-02')->firstOrFail();
        $ccSA01 = CentroComputo::where('codigo', 'CC-SA-01')->firstOrFail();
        $ccSA02 = CentroComputo::where('codigo', 'CC-SA-02')->firstOrFail();

        $mantenimientos = [
            [
                'equipo_id'           => $pcOperativa->id,
                'usuario_id'          => $supervisor->id,
                'tipo'                => 'preventivo',
                'descripcion_trabajo' => 'Limpieza interna de disipadores, cambio de pasta térmica y actualización de BIOS.',
                'piezas_reemplazadas' => 'Pasta térmica Arctic MX-4',
                'fecha_mantenimiento' => Carbon::now()->subDays(30)->toDateString(),
            ],
            [
                'equipo_id'           => $switchCisco->id,
                'usuario_id'          => $admin->id,
                'tipo'                => 'preventivo',
                'descripcion_trabajo' => 'Respaldo de configuración running-config, limpieza de puertos SFP+ y verificación de fuentes redundantes.',
                'piezas_reemplazadas' => null,
                'fecha_mantenimiento' => Carbon::now()->subDays(86)->toDateString(),
            ],
            [
                'equipo_id'           => $servidorNoc->id,
                'usuario_id'          => $admin->id,
                'tipo'                => 'preventivo',
                'descripcion_trabajo' => 'Diagnóstico de arreglo RAID-10, verificación de logs iDRAC y prueba de UPS.',
                'piezas_reemplazadas' => null,
                'fecha_mantenimiento' => Carbon::now()->subDays(15)->toDateString(),
            ],
            // 3 Correctivos consecutivos en INV-SA01-PC05 para activar obtenerCandidatosBaja(3)
            [
                'equipo_id'           => $pcReincidente->id,
                'usuario_id'          => $tecnico->id,
                'tipo'                => 'correctivo',
                'descripcion_trabajo' => 'Falla en encendido por fuente de poder quemada tras fluctuación eléctrica.',
                'piezas_reemplazadas' => 'Fuente de poder ATX 300W',
                'fecha_mantenimiento' => Carbon::now()->subMonths(4)->toDateString(),
            ],
            [
                'equipo_id'           => $pcReincidente->id,
                'usuario_id'          => $tecnico->id,
                'tipo'                => 'correctivo',
                'descripcion_trabajo' => 'Pantallazos azules recurrentes por sectores defectuosos en unidad de almacenamiento.',
                'piezas_reemplazadas' => 'SSD SATA 240GB Kingston',
                'fecha_mantenimiento' => Carbon::now()->subMonths(2)->toDateString(),
            ],
            [
                'equipo_id'           => $pcReincidente->id,
                'usuario_id'          => $tecnico->id,
                'tipo'                => 'correctivo',
                'descripcion_trabajo' => 'Falla crítica en capacitores de placa base; equipo no entrega video.',
                'piezas_reemplazadas' => null,
                'fecha_mantenimiento' => Carbon::now()->subDays(20)->toDateString(),
            ],
        ];

        foreach ($mantenimientos as $mant) {
            Mantenimiento::firstOrCreate(
                [
                    'equipo_id'           => $mant['equipo_id'],
                    'fecha_mantenimiento' => $mant['fecha_mantenimiento'],
                    'tipo'                => $mant['tipo'],
                ],
                $mant
            );
        }

        // 2. Historial de Traslados entre Centros de Cómputo
        $traslados = [
            [
                'equipo_id'      => $pcOperativa->id,
                'cc_origen_id'   => $ccSS02->id,
                'cc_destino_id'  => $ccSS01->id,
                'autorizado_por' => $admin->id,
                'fecha_traslado' => Carbon::now()->subMonths(2)->toDateString(),
                'motivo'         => 'Reasignación de estación de trabajo para prácticas de virtualización y análisis de tráfico.',
            ],
            [
                'equipo_id'      => $pcReincidente->id,
                'cc_origen_id'   => $ccSA02->id,
                'cc_destino_id'  => $ccSA01->id,
                'autorizado_por' => $supervisor->id,
                'fecha_traslado' => Carbon::now()->subMonth()->toDateString(),
                'motivo'         => 'Traslado temporal a laboratorio general para cubrir demanda de matrícula.',
            ],
        ];

        foreach ($traslados as $traslado) {
            HistorialTraslado::firstOrCreate(
                [
                    'equipo_id'      => $traslado['equipo_id'],
                    'fecha_traslado' => $traslado['fecha_traslado'],
                ],
                $traslado
            );
        }

    }
}
