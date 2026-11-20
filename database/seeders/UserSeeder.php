<?php

namespace Database\Seeders;

use App\Models\CentroComputo;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $passwordAdmin = env('ADMIN_PASSWORD');
        $passwordOperativos = env('SEED_USERS_PASSWORD', $passwordAdmin);

        if (empty($passwordAdmin)) {
            throw new RuntimeException('Falta definir ADMIN_PASSWORD en el archivo .env antes de ejecutar UserSeeder.');
        }

        $rolAdmin = Rol::firstOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Acceso total al sistema y gestión de usuarios']
        );

        $rolSupervisor = Rol::firstOrCreate(
            ['nombre' => 'Supervisor'],
            ['descripcion' => 'Gestión de inventario y exportación de reportes']
        );

        $rolTecnico = Rol::firstOrCreate(
            ['nombre' => 'Técnico de Soporte'],
            ['descripcion' => 'Consulta operativa y gestión de mantenimientos']
        );

        $ccSS01 = CentroComputo::where('codigo', 'CC-SS-01')->first();
        $ccSS02 = CentroComputo::where('codigo', 'CC-SS-02')->first();
        $ccSA01 = CentroComputo::where('codigo', 'CC-SA-01')->first();
        $ccSA02 = CentroComputo::where('codigo', 'CC-SA-02')->first();

        $admin = User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@itsecure.sv')],
            [
                'nombre'                => 'Administrador Principal',
                'contrasenha'           => $passwordAdmin,
                'activo'                => true,
                'email_verificado_hace' => now(),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$rolAdmin->id]);

        $supervisorSS = User::updateOrCreate(
            ['email' => 'supervisor.ss@itsecure.sv'],
            [
                'nombre'                => 'Emanuel Domínguez',
                'contrasenha'           => $passwordOperativos,
                'activo'                => true,
                'email_verificado_hace' => now(),
            ]
        );
        $supervisorSS->roles()->sync([$rolSupervisor->id]);
        if ($ccSS01 && $ccSS02) {
            $supervisorSS->centrosDeComputo()->sync([$ccSS01->id, $ccSS02->id]);
        }

        $tecnicoSA = User::updateOrCreate(
            ['email' => 'tecnico.sa@itsecure.sv'],
            [
                'nombre'                => 'Leslie Salmerón',
                'contrasenha'           => $passwordOperativos,
                'activo'                => true,
                'email_verificado_hace' => now(),
            ]
        );
        $tecnicoSA->roles()->sync([$rolTecnico->id]);
        if ($ccSA01 && $ccSA02) {
            $tecnicoSA->centrosDeComputo()->sync([$ccSA01->id, $ccSA02->id]);
        }

        $inactivo = User::updateOrCreate(
            ['email' => 'exempleado@itsecure.sv'],
            [
                'nombre'      => 'Rodrigo Guandique',
                'contrasenha' => $passwordOperativos,
                'activo'      => false,
            ]
        );
        $inactivo->roles()->sync([$rolTecnico->id]);
    }
}
