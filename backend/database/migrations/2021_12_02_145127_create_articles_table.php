<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title")->comment("标题");
            $table->string("sub_title")->comment("子标题");
            $table->text("content")->comment("内容");
            $table->string("author")->comment("作者");
            $table->integer("cate")->comment("分类");
            $table->integer("like")->default(0)->comment("点赞数");
            $table->integer("unlike")->default(0)->comment("不喜欢数");
            $table->integer("vote")->default(0)->comment("投票数");
            $table->text("memo")->nullable();
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
        Schema::dropIfExists('articles');
    }
}
