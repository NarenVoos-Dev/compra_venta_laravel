<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles =[
            ['name' => 'Administrador', 'description' => 'Administrador de la aplicación'],
            ['name' => 'Vendedor', 'description' => 'Se encarga de vender productos'],
            ['name' => 'Mantenedor', 'description' => 'Se encarga del mantenimiento de la aplicación'],
            ['name' => 'Analista', 'description' => 'Se encarga del análisis de la aplicación'],
            ['name' => 'Almacen', 'description' => 'Se encarga del almacenamiento de la aplicación'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
