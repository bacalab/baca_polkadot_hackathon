<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id")->comment("用户ID");
            $table->bigInteger("stake")->default(0);
            $table->integer("aid")->comment("文章ID");
            $table->string("memo")->default("{}");
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
        Schema::dropIfExists('vote_records');
    }
}
