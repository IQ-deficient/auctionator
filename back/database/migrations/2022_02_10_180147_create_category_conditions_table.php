<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category', 64);
            $table->string('condition', 32);
            $table->timestamps();

            // References
            $table->foreign('category')->references('name')->on('categories');
            $table->foreign('condition')->references('name')->on('conditions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_conditions');
    }
}
