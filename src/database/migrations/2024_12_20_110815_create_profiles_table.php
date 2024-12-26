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
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('profile_name', 255);
            $table->string('profile_image', 255)->nullable();
            $table->text('address');
            $table->string('building', 255);
            $table->string('postal_code', 10);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
