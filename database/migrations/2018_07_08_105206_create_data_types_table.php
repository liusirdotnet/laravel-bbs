<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_types', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned()
                ->comment('主键 ID');
            $table->string('name')
                ->unique()
                ->comment('名称');
            $table->string('slug')
                ->unique();
            $table->string('display_name_singular')
                ->comment('单数显示名称');
            $table->string('display_name_plural')
                ->comment('复数显示名称');
            $table->string('icon')
                ->nullable()
                ->comment('图标');
            $table->string('model_name')
                ->nullable();
            $table->string('policy_name')
                ->nullable()
                ->comment('策略名称');
            $table->string('controller')
                ->nullable()
                ->comment('控制器名称');
            $table->string('description')
                ->nullable();
            $table->text('details')
                ->nullable();
            $table->boolean('generate_permissions')
                ->default(false);
            $table->timestamps();
        });

        Schema::create('data_rows', function (Blueprint $table) {
            $table->increments('id')
                ->unsigned()
                ->comment('主键 ID');
            $table->integer('data_type_id')
                ->unsigned()
                ->comment('数据类型 ID');
            $table->string('field')
                ->comment('字段');
            $table->string('type')
                ->comment('类型');
            $table->string('display_name')
                ->comment('显示名称');
            $table->boolean('required')
                ->default(false);
            $table->boolean('access')
                ->default(true)
                ->comment('是否可访问');
            $table->boolean('read')
                ->default(true)
                ->comment('是否可读取');
            $table->boolean('add')
                ->default(true)
                ->comment('是否可添加');
            $table->boolean('edit')
                ->default(true)
                ->comment('是否可编辑');
            $table->boolean('delete')
                ->default(true)
                ->comment('是否可删除');
            $table->unsignedMediumInteger('order')
                ->default(0)
                ->comment('数据列排序');
            $table->text('details')
                ->nullable()
                ->comment('数据列详情');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_types');
        Schema::dropIfExists('data_rows');
    }
}
