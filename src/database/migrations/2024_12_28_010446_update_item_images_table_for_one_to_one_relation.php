<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateItemImagesTableForOneToOneRelation extends Migration
{

    public function up()
    {
        Schema::table('item_images', function (Blueprint $table) {
            $table->unique('item_id');
        });
    }


    public function down()
    {
        Schema::table('item_images', function (Blueprint $table) {
            $table->dropUnique(['item_id']);
        });
    }
}
