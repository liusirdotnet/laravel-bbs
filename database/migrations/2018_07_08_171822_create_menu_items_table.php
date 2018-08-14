<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id')
                ->comment('主键 ID');
            $table->integer('menu_id')
                ->unsigned()
                ->nullable()
                ->comment('菜单 ID');
            $table->integer('parent_id')
                ->nullable()
                ->default(0)
                ->comment('菜单项父级 ID');
            $table->string('title')
                ->comment('菜单项名称');
            $table->string('url')
                ->default('')
                ->comment('菜单项地址');
            $table->string('route')
                ->nullable()
                ->default('')
                ->comment('菜单项路由');
            $table->string('target')
                ->default('_self');
            $table->string('icon_class')
                ->nullable()
                ->comment('菜单项图标');
            $table->string('color')
                ->nullable()
                ->default('')
                ->comment('菜单项颜色');
            $table->integer('order')
                ->default(0)
                ->comment('菜单项排序');
            $table->text('parameters')
                ->nullable()
                ->default(null)
                ->comment('菜单项参数');
            $table->timestamps();
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreign('menu_id')
                ->references('id')
                ->on('menus')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
