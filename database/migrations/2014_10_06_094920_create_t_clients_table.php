<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_clients', function (Blueprint $table) {
            $table->id();
            $table->string('codeclient');
            $table->string('libtiers');
            $table->string('adressepostale')->nullable();
            $table->string('adressegeo')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('numerocomtribuabe')->nullable();
            $table->string('numerodecompte')->nullable();
            $table->string('capital')->nullable();
            $table->string('sitelivraison')->nullable();
            $table->unsignedBigInteger('modelivraison')->nullable();
            $table->unsignedBigInteger('tregimefiscal_id')->nullable();
            $table->unsignedBigInteger('tcodedevise_id')->nullable();
            $table->foreign('tcodedevise_id')->references('id')->on('tcode_devises');
            $table->foreign('tregimefiscal_id')->references('id')->on('tregime_fiscals');
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
        Schema::dropIfExists('t_clients');
    }
}
