<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('starting_price', 10, 2);
            $table->decimal('min_price', 10, 2);
            $table->decimal('max_price', 10, 2);
            $table->decimal('reference_price', 10, 2);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['available', 'closed', 'cancelled'])->default('available');
            $table->timestamp('expiration_date')->nullable();
            $table->decimal('final_price', 10, 2)->nullable();
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
        Schema::dropIfExists('auctions');
    }
}
