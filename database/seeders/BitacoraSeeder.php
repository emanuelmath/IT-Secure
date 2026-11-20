<?php

namespace Database\Seeders;

use App\Models\Bitacora;
use App\Models\Equipo;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class BitacoraSeeder extends Seeder
{
    public function run(): void
    {
        if (Bitacora::exists()) {
            return;
        }

        $emailAdmin = env('ADMIN_EMAIL');

        if (empty($emailAdmin)) {
            throw new RuntimeException('Falta definir ADMIN_EMAIL en el archivo .env antes de ejecutar BitacoraSeeder.');
        }

        $admin       = User::where('email', $emailAdmin)->first();
        $supervisor  = User::where('email', 'supervisor.ss@itsecure.sv')->first();
        $tecnico     = User::where('email', 'tecnico.sa@itsecure.sv')->first();
        $servidorNoc = Equipo::where('codigo_inventario', 'INV-NOC-SRV01')->first();
        $pcAveriada  = Equipo::where('codigo_inventario', 'INV-SA01-PC05')->first();

        Bitacora::registrar(
            'Sistema',
            'CREAR',
            'Inicialización de estructura institucional, roles y cuentas base mediante Seeder.',
            null,
            $admin?->id,
            $admin?->email ?? 'Sistema'
        );

        if ($admin) {
            Bitacora::registrar(
                'Autenticación',
                'LOGIN_OK',
                'Inicio de sesión administrativo verificado.',
                $admin->id,
                $admin->id,
                $admin->email
            );
        }

        if ($servidorNoc) {
            Bitacora::registrar(
                'Equipos',
                'CREAR',
                "Alta de activo crítico {$servidorNoc->codigo_inventario} ({$servidorNoc->marca} {$servidorNoc->modelo}).",
                $servidorNoc->id,
                $supervisor?->id ?? $admin?->id,
                $supervisor?->email ?? $admin?->email ?? 'Sistema'
            );
        }

        if ($pcAveriada) {
            Bitacora::registrar(
                'Mantenimientos',
                'MANTENIMIENTO',
                "Registro de falla crítica reincidente en {$pcAveriada->codigo_inventario}.",
                $pcAveriada->id,
                $tecnico?->id ?? $admin?->id,
                $tecnico?->email ?? $admin?->email ?? 'Sistema'
            );
        }
    }
}
