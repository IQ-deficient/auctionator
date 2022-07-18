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
            $table->string('title', 128);
            $table->string('seller', 32);   // Client registered in paperwork that is selling the Item
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('bid_id')->nullable();
            $table->unsignedDouble('min_bid_value');   // Minimum price the bidding for the Auction must start at
            $table->unsignedDouble('buyout');   // Final purchase value in Euro
            $table->string('status', 32);   // auction lifecycle
            $table->dateTime('start_datetime')->default(Carbon::now());
            $table->dateTime('end_datetime')->default(Carbon::tomorrow());
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_active')->default(true);    // new or 'deleted' Auctions
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
