<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryItemsTable extends Migration
{

    public function up()
    {
        Schema::create('category_items', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->primary(['category_id', 'item_id']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('category_items');
    }
}
