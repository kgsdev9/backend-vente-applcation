<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_documents', function (Blueprint $table) {
            $table->id();
            $table->string('libelledocument');
            $table->string('fichierdocument');
            $table->unsignedBigInteger('dossier_id');
            $table->foreign('dossier_id')->references('id')->on('t_dossiers');
            $table->timestamps();
        });
    }

    /**   'libelledocument',
        'fichierdocument',
        ''
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_documents');
    }
}
