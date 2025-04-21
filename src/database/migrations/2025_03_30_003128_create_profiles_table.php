<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{

    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('img_url', 255)->nullable();
            $table->string('postcode', 255);
            $table->string('address', 255);
            $table->string('building', 255)->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
