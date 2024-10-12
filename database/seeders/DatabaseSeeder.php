<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(ModeReglementSeeder::class);
        $this->call(RegimeVenteSeeder::class);
        $this->call(TCodeDeviseSeeder::class);
        $this->call(TConditionVenteSeeder::class);
        $this->call(TDepartementSeeder::class);
        $this->call(RegimeFiscalSeeder::class);
        \App\Models\User::factory(30)->create();
        \App\Models\TClient::factory(30)->create();
    }
}
