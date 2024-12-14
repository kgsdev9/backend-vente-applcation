<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTBeneficeVentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_benefice_ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numvente');
            $table->string('codecommande');
            $table->foreignId('tproduct_id')->constrained('t_products')->onDelete('cascade');
            $table->string('prixachat');
            $table->string('prixdevente');
            $table->string('benefice');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_benefice_ventes');
    }
}
