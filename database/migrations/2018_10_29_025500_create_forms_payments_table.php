<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('detalle');
            $table->timestamps();
        });

        DB::table('forms_payments')->insert(array(
            array('detalle' => 'Efectivo'),
            array('detalle' => 'Cheque'),
            array('detalle' => 'Cartera'), 
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms_payments');
    }
}
