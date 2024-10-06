<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTEtudetechniquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_etudetechniques', function (Blueprint $table) {
            $table->id();
            $table->string('numetudetech');
            $table->unsignedBigInteger('tclient_id');
            $table->boolean('statutet')->default('0');
            $table->foreign('tclient_id')->references('id')->on('tclients');
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
        Schema::dropIfExists('t_etudetechniques');
    }
}
