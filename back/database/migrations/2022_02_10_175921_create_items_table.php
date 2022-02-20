<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 64);
            $table->text('description');                // We will see how this works and if it is safe
            $table->string('category', 64);
            $table->string('condition', 32);
            $table->unsignedBigInteger('warehouse_id');
            $table->timestamps();

            // References
            $table->foreign('category')->references('name')->on('categories');
            $table->foreign('condition')->references('name')->on('conditions');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
