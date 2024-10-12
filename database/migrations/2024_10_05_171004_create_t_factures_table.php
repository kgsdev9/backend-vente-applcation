<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_factures', function (Blueprint $table) {
            $table->id();
            $table->string('codefacture');
            $table->string('numcommande')->nullable();
            $table->string('numvente')->nullable();
            $table->date('date_echance')->nullable();
            $table->integer('tva')->nullable();
            $table->integer('adsci')->nullable();
            $table->integer('montantht')->nullable();
            $table->integer('numcpteclient')->nullable();
            $table->integer('montantttc')->nullable();
            $table->string('numcptecontribuable')->nullable();
            $table->unsignedBigInteger('tmodereglement_id');
            $table->unsignedBigInteger('tclient_id');
            $table->unsignedBigInteger('tcodedevise_id');
            $table->unsignedBigInteger('tregimevente_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tconditonvte_id')->nullable();
            $table->foreign('tclient_id')->references('id')->on('t_clients');
            $table->foreign('tmodereglement_id')->references('id')->on('tmode_reglements');
            $table->foreign('tcodedevise_id')->references('id')->on('tcode_devises');
            $table->foreign('tregimevente_id')->references('id')->on('tregime_ventes');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tconditonvte_id')->references('id')->on('t_conditon_vtes');
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
        Schema::dropIfExists('t_factures');
    }
}
