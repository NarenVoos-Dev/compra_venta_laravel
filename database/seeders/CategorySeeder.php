<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name'=>'Laptops','description'=>'Computadoras portatiles de diferentes marcas'],
            ['name'=>'Monitores','description'=>'Pantallas y monitores de altas resoluciones'],
            ['name'=>'Tablets','description'=>'Tabletas portátiles de diferentes marcas'],
            ['name'=>'Smartphones','description'=>'Celulares inteligentes de diferentes marcas'],
            ['name'=>'Accessories','description'=>'Accesorios para computadoras'],
            ['name'=>'Cameras','description'=>'Cámaras de diferentes marcas'],
            ['name'=>'Speakers','description'=>'Altavoces de diferentes marcas'],
            ['name'=>'Headphones','description'=>'Auriculares de diferentes marcas'],
            ['name'=>'Redes','description'=>'Routers,modem,switches y otros componentes de red'],
            ['name'=>'Servidores','description'=>'Servidores de diferentes marcas'],
            ['name'=>'Software','description'=>'Software de diferentes marcas'],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
