<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTLigneCmdeReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ligne_cmde_references', function (Blueprint $table) {
            $table->id();
            $table->string('numcmderefe')->unique();
            $table->string('reference');
            $table->integer('numligne');
            $table->integer('prixunitaire')->nullable();
            $table->integer('quantite')->nullable();
            $table->integer('remiseligne')->nullable();
            $table->string('montantht')->nullable();
            $table->unsignedBigInteger('tcommandereference_id')->nullable();
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
        Schema::dropIfExists('t_ligne_cmde_references');
    }
}
