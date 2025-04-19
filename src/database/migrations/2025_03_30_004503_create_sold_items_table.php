<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldItemsTable extends Migration
{

    public function up()
    {
        Schema::create('sold_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->string('sending_postcode', 255);
            $table->string('sending_address', 255);
            $table->string('sending_building', 255)->nullable();
            $table->enum('payment_method', ['card', 'convenience_store']);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('sold_items');
    }
}
