<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['name' => 'Alimentos Empacados'],
            ['name' => 'Frutas y Verduras'],
            ['name' => 'Refrigerados'],
            ['name' => 'Bebidas'],
            ['name' => 'Cuidado Personal'],
            ['name' => 'Niños y Bebés'],
            ['name' => 'Mascotas'],
            ['name' => 'Suministros Escolares y Oficina'],
            ['name' => 'Artículos de Limpieza'],
            ['name' => 'Productos de Belleza'],
        ];

        DB::table('sections')->insert($sections);
    }
}
