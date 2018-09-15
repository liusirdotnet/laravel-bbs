<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned()
                ->comment('主键 ID');
            $table->integer('user_id')
                ->unsigned()
                ->default(0)
                ->index('idx_user_id')
                ->comment('用户 ID');
            $table->enum('type', ['avatar', 'topic'])
                ->nullable()
                ->default('avatar')
                ->index('idx_avatar')
                ->comment('图片类型');
            $table->string('path')
                ->default('')
                ->index('idx_path')
                ->comment('图片路径');
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
        Schema::dropIfExists('images');
    }
}
