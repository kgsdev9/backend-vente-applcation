<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTReferenceClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_reference_clients', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->integer('prixunitaire');
            $table->integer('qte')->nullable();
            $table->string('numetudeclient');
            $table->string('numetudeprixclient');
            $table->unsignedBigInteger('t_client_id');
            $table->foreign('t_client_id')->references('id')->on('t_clients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *   'reference',
        'prix',
        'client_id'
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_reference_clients');
    }
}
