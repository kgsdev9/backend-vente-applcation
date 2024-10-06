<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTMontantTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_montant_transports', function (Blueprint $table) {
            $table->id();
            $table->string('montanttransport');
            $table->unsignedBigInteger('tfacture_id');
            $table->foreign('tfacture_id')->references('id')->on('tfactures');
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
        Schema::dropIfExists('t_montant_transports');
    }
}
