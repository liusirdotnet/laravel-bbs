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
            $table->integer('parent_id')
                ->nullable()
                ->default(0)
                ->comment('菜单条目父级 ID');
            $table->integer('menu_id')
                ->unsigned()
                ->nullable()
                ->comment('菜单 ID');
            $table->string('title')
                ->comment('菜单条目名称');
            $table->string('url')
                ->comment('菜单条目地址');
            $table->string('route')
                ->nullable()
                ->default(null)
                ->comment('菜单条目路由');
            $table->string('target')
                ->default('_self');
            $table->string('icon_class')
                ->nullable()
                ->comment('菜单条目 Icon');
            $table->string('color')
                ->nullable();
            $table->integer('order')
                ->comment('菜单条目排序');
            $table->text('parameters')
                ->nullable()
                ->default(null);
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
