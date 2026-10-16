<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Empresa::Factory()->count(20)->create();
        /*DB::table('empresas')->insert([
            'nombre' => 'Universidad Modular Abierta',
            'nit' => '12345678901234567890'
        ]);*/
    }
}
