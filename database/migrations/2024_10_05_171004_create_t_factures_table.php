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
            $table->string('codefacture')->nullable();
            $table->decimal('remise', 8, 2)->nullable();
            $table->string('numcommande')->nullable();
            $table->string('numvente')->nullable();
            $table->string('libelleclient')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('codeclient')->nullable();
            $table->string('adresse')->nullable();
            $table->date('date_echance')->nullable();
            $table->unsignedBigInteger('mode_reglement_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('codedevise_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('montanttva', 8, 2)->nullable();
            $table->date('dateecheance')->nullable();
            $table->decimal('montantadsci', 8, 2)->nullable();
            $table->unsignedBigInteger('idregimevente')->nullable();
            $table->unsignedBigInteger('idconditionvte')->nullable();
            $table->decimal('montantht', 8, 2)->nullable();
            $table->string('numcpteclient')->nullable();
            $table->string('numcptecontribuable')->nullable();
            $table->decimal('montantttc', 8, 2)->nullable();
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
