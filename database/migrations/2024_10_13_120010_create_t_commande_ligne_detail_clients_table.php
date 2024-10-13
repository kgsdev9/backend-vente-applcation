<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCommandeLigneDetailClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_commande_ligne_detail_clients', function (Blueprint $table) {
            $table->id();
            $table->string('numdetailcmde')->unique();
            $table->unsignedBigInteger('codecmde');
            $table->string('reference')->unique();
            $table->integer('numligne');
            $table->integer('prixunitaire')->nullable();
            $table->integer('quantitecmde')->nullable();
            $table->integer('quantitelivre')->nullable();
            $table->integer('reliquat')->nullable();
            $table->integer('remiseligne')->nullable();
            $table->string('montantht')->nullable();
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
        Schema::dropIfExists('t_commande_ligne_detail_clients');
    }
}
