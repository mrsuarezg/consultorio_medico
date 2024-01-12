<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class ContraceptiveMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contraceptiveMethods = [
            [
                'name' => 'Preservativo masculino',
            ],
            [
                'name' => 'Diafragma',
            ],
            [
                'name' => 'Dispositivo intrauterino (DIU)',
            ],
            [
                'name' => 'Espermicidas',
            ],
            [
                'name' => 'Esterilización',
            ],
            [
                'name' => 'Implante subdérmico',
            ],
            [
                'name' => 'Inyección anticonceptiva',
            ],
            [
                'name' => 'Método de la amenorrea de la lactancia (MELA)',
            ],
            [
                'name' => 'Método de la temperatura basal',
            ],
            [
                'name' => 'Método del moco cervical o Billings',
            ],
            [
                'name' => 'Método del ritmo',
            ],
            [
                'name' => 'Píldora anticonceptiva',
            ],
            [
                'name' => 'Píldora de emergencia',
            ],
            [
                'name' => 'Preservativo femenino',
            ],
            [
                'name' => 'Vasectomía',
            ],
            [
                'name' => 'Ligadura de trompas',
            ],
            [
                'name' => 'Otro',
            ],
            [
                'name' => 'Ninguno',
            ],
        ];

        \App\Models\ContraceptiveMethod::insert($contraceptiveMethods);
    }
}
