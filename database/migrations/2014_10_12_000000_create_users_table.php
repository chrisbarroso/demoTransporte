<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 60)->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(array(
            array('name' => 'Administrador', 'email' => 'administracion@pluckymind.com', 'password' => '$2y$10$AUTYKu3H1LEXzR7E8FB2Z.XmdBwE1ro0AQHG/FKZaWCM6fRsj9PKq', 'remember_token' => NULL, 'created_at' => '2018-01-19 15:49:33', 'updated_at' => '2018-01-19 15:49:33'), 
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
