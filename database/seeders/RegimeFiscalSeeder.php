<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegimeFiscalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tregime_fiscals')->insert([
            ['libelleregimefiscal'=> 'Régime de la Taxe sur la Valeur Ajoutée (TVA)'] ,
            ['libelleregimefiscal'=> 'Régime du Réel Normal'] ,
            ['libelleregimefiscal'=> 'Régime du Réel Simplifié'],
            ['libelleregimefiscal'=> 'Régime de la Microentreprise'] ,
            ['libelleregimefiscal'=> 'Régime de l\'Exportation'] ,
            ['libelleregimefiscal'=> 'Régime d\'Exonération Partielle ou Totale'] ,
            ['libelleregimefiscal'=> 'Régime des Zones Franches']
        ]);
    }
}
