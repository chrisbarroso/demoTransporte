<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWaybillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waybills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idCliente');
            $table->integer('ncporte');
            $table->integer('idLugarPartida'); 
            $table->integer('idLugarDestino'); 
            $table->date('fecha');
            $table->integer('kilometro');
            $table->float('tarifa_transporte');
            $table->float('tarifa_cliente');
            $table->integer('id_chofer');
            $table->integer('id_unidad');
            $table->integer('id_dueno');
            $table->float('kilo');
            $table->integer('porcentaje');
            $table->float('importe_transporte');
            $table->float('importe_porcentaje');
            $table->float('importe_cliente');
            $table->boolean('liquidado')->default(false);  
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
        Schema::dropIfExists('waybills');
    }
}
