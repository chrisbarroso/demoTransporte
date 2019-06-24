<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advancements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_chofer');
            $table->integer('id_unidad');
            $table->integer('id_dueno');
            $table->date('fecha');
            $table->float('importe');
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
        Schema::dropIfExists('advancements');
    }
}
