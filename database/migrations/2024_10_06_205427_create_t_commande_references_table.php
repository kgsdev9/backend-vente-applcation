<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCommandeReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_commande_references', function (Blueprint $table) {
            $table->id();
            $table->string('codecomde');
            $table->string('client_id')->nullable();
            $table->string('datecomde')->nullable();
            $table->date('delailivraisoncmde')->nullable();
            $table->unsignedBigInteger('dateconfirmation');
            $table->unsignedBigInteger('remiseglobale');
            $table->unsignedBigInteger('tzonelivraison_id');
            $table->unsignedBigInteger('montantadsci')->nullable();
            $table->unsignedBigInteger('montanttva')->nullable();
            $table->unsignedBigInteger('montantht')->nullable();
            $table->boolean('facturer')->define('0');
            $table->integer('montanttc')->nullable();
            $table->integer('qtecmde')->nullable();
            $table->integer('qtelivrer')->nullable();
            $table->integer('qterestante')->nullable();
            $table->integer('statutcmde')->default('0');
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
        Schema::dropIfExists('t_commande_references');
    }
}
