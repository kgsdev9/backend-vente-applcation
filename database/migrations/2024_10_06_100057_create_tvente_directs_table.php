<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTventeDirectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tvente_directs', function (Blueprint $table) {
            $table->id();
            $table->integer('numvte');
            $table->integer('numprofvte');
            $table->integer('libtiers');
            $table->integer('telephone');
            $table->integer('fax');
            $table->integer('datevte');
            $table->integer('statutvente');
            $table->integer('montantht');
            $table->string('montanttc');
            $table->integer('montanttva')->nullable();
            $table->integer('montantadsci')->nullable();
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
        Schema::dropIfExists('tvente_directs');
    }
}
