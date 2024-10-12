<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegimeVenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tregime_ventes')->insert([
            ['libellergmevte'=> 'Consommation Locale'] ,
            ['nom'=> 'Admission temporaire'] ,
            ['nom'=> 'Exportation Directe'] ,
        ]);
    }
}

