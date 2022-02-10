<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class CreateAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 128);    // was 32 length (we might need more space for full title)
            $table->string('seller', 32);   // Client selling the item registered in paperwork
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('bid_id')->nullable();
            $table->unsignedDouble('buyout');   // Value in Euro
            $table->string('status', 32);
            $table->dateTime('start_datetime')->default(Carbon::now());
            $table->dateTime('end_datetime')->default(Carbon::now());
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // references
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('bid_id')->references('id')->on('bids');
            $table->foreign('status')->references('status')->on('statuses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auctions');
    }
}
