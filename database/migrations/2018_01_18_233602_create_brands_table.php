<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('detalle');
        });

        DB::table('brands')->insert(array(
            array('detalle' => 'Bedford'),
            array('detalle' => 'Chevrolet'),
            array('detalle' => 'Dodge'),
            array('detalle' => 'Fiat'),
            array('detalle' => 'Ford'),
            array('detalle' => 'Iveco'),
            array('detalle' => 'KIA'),
            array('detalle' => 'Mercedes-Benz'),
            array('detalle' => 'Nissan'),
            array('detalle' => 'Peugeot'),
            array('detalle' => 'Rastrojero'),
            array('detalle' => 'Renault'),
            array('detalle' => 'Scania'),
            array('detalle' => 'Toyota'),
            array('detalle' => 'Volkswagen'),
            array('detalle' => 'Volvo'),
        ));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}
