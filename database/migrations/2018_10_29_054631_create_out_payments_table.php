<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_forma_pago');
            $table->string('concepto');
            $table->integer('id_sobre_concepto');
            $table->float('importeF')->nullable();
            $table->integer('nro_cheque')->nullable();
            $table->integer('id_cartera')->nullable();
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
        Schema::dropIfExists('out_payments');
    }
}
