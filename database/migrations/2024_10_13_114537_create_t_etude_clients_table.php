<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTEtudeClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_etude_clients', function (Blueprint $table) {
            $table->id();
            $table->string('numeetudeclient');
            $table->string('numetudeprixclient');
            $table->unsignedBigInteger('tclient_id');
            $table->decimal('montant_etude', 10, 2)->nullable();
            $table->enum('statutet', ['0', '1', '2'])->default('0');
            $table->integer('duree_traitement');
            $table->string('responsable_etude')->nullable();
            $table->unsignedBigInteger('redacteur_id');
            $table->foreign('tclient_id')->references('id')->on('t_clients')->onDelete('cascade');
            $table->foreign('redacteur_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('t_etude_clients');
    }
}
