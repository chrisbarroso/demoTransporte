<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_chofer');
            $table->integer('id_unidad');
            $table->integer('id_dueno');
            $table->integer('id_proveedor');
            $table->date('fecha');
            $table->integer('nro_control')->nullable();
            $table->float('importe')->nullable();
            $table->integer('litros')->nullable();
            $table->boolean('tanque_lleno')->default(false);
            $table->boolean('confirmado')->default(false);
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
        Schema::dropIfExists('purchases');
    }
}
