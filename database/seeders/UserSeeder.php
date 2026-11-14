<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdmin = Rol::firstOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Acceso total al sistema y gestión de usuarios']
        );

        Rol::firstOrCreate(
            ['nombre' => 'Supervisor'],
            ['descripcion' => 'Gestión de inventario y exportación de reportes']
        );

        Rol::firstOrCreate(
            ['nombre' => 'Técnico de Soporte'],
            ['descripcion' => 'Consulta operativa y gestión de perfil']
        );

        $emailAdmin = env('ADMIN_EMAIL', 'admin@itsecure.sv');
        $passwordAdmin = env('ADMIN_PASSWORD', 'Admin1234*');

        $admin = User::updateOrCreate(
            ['email' => $emailAdmin],
            [
                'nombre' => 'Administrador Principal',
                'contrasenha' => $passwordAdmin,
                'activo' => true,
            ]
        );

        $admin->roles()->syncWithoutDetaching([$rolAdmin->id]);
    }
}
