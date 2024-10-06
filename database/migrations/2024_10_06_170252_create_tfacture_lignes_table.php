<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfactureLignesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tfacture_lignes', function (Blueprint $table) {
            $table->id();
            $table->string('codefacture')->unique();
            $table->string('reference');
            $table->string('prixunitaire');
            $table->string('quantite');
            $table->string('remeligne');
            $table->string('tva');
            $table->string('montantadsci');
            $table->string('montanttc');
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
        Schema::dropIfExists('tfacture_lignes');
    }
}
