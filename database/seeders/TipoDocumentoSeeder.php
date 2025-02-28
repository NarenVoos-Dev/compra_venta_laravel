<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;
class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $TipoDocumento = [
            ['name'=>'Factura','description'=>'Documento utilizado para ventas', 'type'=>'venta'],
            ['name'=>'Boleta','description'=>'Documento utilizado para ventas', 'type'=>'venta'],
            ['name'=>'Nota de Credito','description'=>'Documento utilizado para compras', 'type'=>'compra'],

        ];

        foreach ($TipoDocumento as $tipoDocumento) {
            TipoDocumento::create($tipoDocumento);
        }   
    }
}
