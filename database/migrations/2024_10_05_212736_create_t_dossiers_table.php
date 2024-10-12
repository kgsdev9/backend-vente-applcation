<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTDossiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_dossiers', function (Blueprint $table) {
            $table->id();
            $table->string('libelledossier');
            $table->string('codedossier')->nullable();
            $table->unsignedBigInteger('tdepartement_id')->nullable();
            $table->foreign('tdepartement_id')->references('id')->on('t_departements');
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
        Schema::dropIfExists('t_dossiers');
    }
}
