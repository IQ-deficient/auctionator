<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

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
            $table->bigIncrements('id');
            $table->string('username', 32)->unique();
            $table->string('password', 128);
            $table->string('first_name', 32);
            $table->string('last_name', 32);
            $table->string('email', 254)->unique();
            $table->string('phone_number', 15)->unique();
            $table->string('gender', 32)->nullable();
            $table->string('country', 80);      // Not nullable because of Phone Number prefix
            $table->dateTime('birthdate')->nullable()->default(null);
            $table->string('image')->nullable();    // User image   // Unsure if this works for the image storing
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            // References
            $table->foreign('gender')->references('name')->on('genders');
            $table->foreign('country')->references('name')->on('countries');
        });
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
