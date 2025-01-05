<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBrandToItemsTable extends Migration
{

    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('brand')->nullable(); // ブランド名を保存（null許可）
        });
    }


    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('brand'); // カラムを削除する場合
        });
    }
}
