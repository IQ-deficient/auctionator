<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 32);
            $table->string('role', 32);
            $table->timestamps();

            // Cascade on Update simply means that this will also be updated if parent row (username on users) is updated
            $table->foreign('username')->references('username')->on('users')->onUpdate('cascade');
            $table->foreign('role')->references('name')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
