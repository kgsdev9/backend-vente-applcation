<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTFournisseursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('codefournisseur');
            $table->string('libellefournisseur');
            $table->string('adresse')->nullable();
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('telephone')->nullable();
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
        Schema::dropIfExists('t_fournisseurs');
    }
}
