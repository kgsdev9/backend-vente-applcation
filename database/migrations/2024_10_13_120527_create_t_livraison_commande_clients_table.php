<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTLivraisonCommandeClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_livraison_commande_clients', function (Blueprint $table) {
            $table->id();
            $table->string('numlivraison');
            $table->string('codecmde')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->date('datelivraison')->nullable();
            $table->date('quantitelivre');
            $table->integer('reliquat');
            $table->integer('statutlivr')->default('0');
            $table->unsignedBigInteger('codecommercial')->nullable();
            $table->foreign('client_id')->references('id')->on('t_clients');
            $table->foreign('codecommercial')->references('id')->on('users');
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
        Schema::dropIfExists('t_livraison_commande_clients');
    }
}
