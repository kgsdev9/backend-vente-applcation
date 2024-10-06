<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTventeDirectLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvente_direct_lignes', function (Blueprint $table) {
            $table->id();
            $table->integer('numvte');
            $table->integer('numprofvte');
            $table->integer('numligne');
            $table->integer('libtiers');
            $table->integer('telephone');
            $table->integer('reference');
            $table->integer('prixunitaire');
            $table->integer('quantite');
            $table->integer('remiseligne');
            $table->string('montantht');
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
        Schema::dropIfExists('tvente_direct_lignes');
    }
}
