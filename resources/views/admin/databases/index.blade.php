@extends('admin.layouts.app')

@section('page_title', '数据库')

@section('page_header')
  <h1 class="page-title">
    <i class="voyager-data"></i> 数据库
    <a href="{{ route('admin.databases.create') }}" class="btn btn-success">
      <i class="voyager-plus"></i> 创建一个新表
    </a>
  </h1>
@stop

@section('content')
  <div class="page-content browse container-fluid">
    @include('admin.components.alert')
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-bordered">
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table table-striped database-tables">
                <thead>
                <tr>
                  <th>表名</th>
                  <th style="text-align:right" colspan="2">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($tables as $table)
                  @continue(in_array($table->name, config('admin.database.tables.hidden', []), true))
                  <tr>
                    <td>
                      <p class="name">
                        <a href="{{ route('admin.databases.show', $table->name) }}" data-name="{{ $table->name }}"
                           class="desctable">
                          {{ $table->name }}
                        </a>
                      </p>
                    </td>
                    <td>
                      <div class="bread_actions">
                        @if($table->dataTypeId)
                          <a href="" class="btn-sm btn-warning browse_bread">
                            <i class="voyager-plus"></i> 查看 Bread
                          </a>
                          <a href="" class="btn-sm btn-default edit">
                            编辑 Bread
                          </a>
                          <a data-id="{{ $table->dataTypeId }}" data-name="{{ $table->name }}"
                             class="btn-sm btn-danger delete">
                            删除 Bread
                          </a>
                        @else
                          <a href="" class="btn-sm btn-default">
                            <i class="voyager-plus"></i> 添加 Bread
                          </a>
                        @endif
                      </div>
                    </td>
                    <td class="actions">
                      <a
                        class="btn btn-danger btn-sm pull-right delete_table @if($table->dataTypeId) remove-bread-warning @endif"
                        data-table="{{ $table->name }}">
                        <i class="voyager-trash"></i> 删除
                      </a>
                      <a href="{{ route('admin.databases.edit', $table->name) }}"
                         class="btn btn-sm btn-primary pull-right"
                         style="display:inline; margin-right:10px;">
                        <i class="voyager-edit"></i> 编辑
                      </a>
                      <a href="{{ route('admin.databases.show', $table->name) }}" data-name="{{ $table->name }}"
                         class="btn btn-sm btn-warning pull-right desctable"
                         style="display:inline; margin-right:10px;">
                        <i class="voyager-eye"></i> 查看
                      </a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
