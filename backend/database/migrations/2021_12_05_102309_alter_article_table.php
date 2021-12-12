<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('cover_img', 512)->after("sub_title")->nullable(); //增加
            $table->integer('is_top_vote')->after("cover_img")->default(0); //增加
//            $table->string('memo', 1024)->nullable()->change(); //修改
//            $table->renameColumn('c', 'd'); //重命名
//            $table->dropColumn(['e', 'f', 'g']);//删除
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
//            $table->string('cover_img', 512)->nullable(); //增加
//            $table->string('memo', 1024)->nullable()->change(); //修改
//            $table->renameColumn('c', 'd'); //重命名
            $table->dropColumn(['cover_img']);//删除
        });
    }
}
