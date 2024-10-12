<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModeReglementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tmode_reglements')->insert([
            ['libellemodereglement'=> 'Cheque'] ,
            ['nom'=> 'Virement Bancaire'] ,
            ['nom'=> 'En Espece'] ,
        ]);
    }
}
