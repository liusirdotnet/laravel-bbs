<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->increments('id')
                  ->comment('主键 ID');
            $table->integer('topic_id')
                  ->unsigned()
                  ->default(0)
                  ->index()
                  ->comment('话题 ID');
            $table->integer('user_id')
                  ->unsigned()
                  ->default(0)
                  ->index()
                  ->comment('用户 ID');
            $table->text('content')
                  ->comment('回复内容');
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
        Schema::dropIfExists('replies');
    }
}
