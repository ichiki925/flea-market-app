<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{

    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->text('address');
            $table->string('building', 255);
            $table->string('postal_code', 10);
            $table->enum('payment_method', ['card', 'convenience_store']);
            $table->timestamps();

            $table->unique(['item_id', 'buyer_id']);

        });
    }


    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
