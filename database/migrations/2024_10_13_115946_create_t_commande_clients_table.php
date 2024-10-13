<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTCommandeClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_commande_clients', function (Blueprint $table) {
            $table->id();
            $table->string('codecomde');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->date('datecmde')->nullable();
            $table->date('delailivrcmde')->nullable();
            $table->date('dateconfirmation');
            $table->integer('quantitecmde');
            $table->integer('quantitelivre')->nullable();
            $table->integer('reliquat')->nullable();
            $table->integer('montantadsci')->nullable();
            $table->integer('montanttva')->nullable();
            $table->integer('montantht')->nullable();
            $table->integer('montanttc')->nullable();
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
        Schema::dropIfExists('t_commande_clients');
    }
}
