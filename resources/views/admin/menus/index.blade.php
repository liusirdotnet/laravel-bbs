@extends('admin.layouts.app')

@section('title', '菜单列表')

@section('page_header')
  <div class="container-fluid">
    <h1 class="page-title">
      <i class="voyager-lock"></i> 菜单
    </h1>

    @can('add', app($dataType->model_name))
      <a href="{{ route('admin.roles.create') }}" class="btn btn-success btn-add-new">
        <i class="voyager-plus"></i> <span>添加菜单</span>
      </a>
    @endcan

    @can('delete', app($dataType->model_name))
      @include('admin.partials.delete')
    @endcan

    @can('edit', app($dataType->model_name))
      @if(isset($dataType->order_column) && isset($dataType->order_display_column))
        <a href="#" class="btn btn-primary">
          <i class="voyager-list"></i> <span>排序</span>
        </a>
      @endif
    @endcan
  </div>
@stop

@section('content')
  <div class="page-content browse container-fluid">
    @include('admin.components.alert')
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-bordered">
          <div class="panel-body">
            <form class="form-inline well well-sm" id="log-search-form" action="#" method="get" novalidate="">
              <div class="form-group">
                <select class="form-control" id="search_key" name="key">
                  @foreach($searchable as $key)
                    <option
                        value="{{ $key }}" @if($search->key === $key){{ 'selected' }}@endif>{{ ucfirst(camel_case($key)) }}</option>
                  @endforeach
                </select>
                <select class="form-control" id="filter" name="filter">
                  <option value="contains" @if($search->filter === "contains"){{ 'selected' }}@endif>包含</option>
                  <option value="equals" @if($search->filter === "equals"){{ 'selected' }}@endif>相等</option>
                </select>
                <input type="text" class="form-control" name="s" value="{{ $search->value }}" placeholder="请输入关键字搜索...">
              </div>
              <button class="btn btn-primary" type="submit"><i class="voyager-search"></i> 搜索</button>
            </form>
            <div class="table-responsive">
              <table id="dataTable" class="table table-hover">
                <thead>
                <tr>
                  @can('delete', app($dataType->model_name))
                    <th>
                      <input type="checkbox" class="select_all">
                    </th>
                  @endcan
                  <th>名称</th>
                  <th class="actions text-right">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataTypeContent as $instance)
                  <tr>
                    @can('delete', app($dataType->model_name))
                      <td>
                        <input type="checkbox" name="row_id" id="checkbox_" value="">
                      </td>
                    @endcan

                    @foreach($dataType->accessRows as $row)
                      <td>
                        @if($row->type === 'image')
                          <span>image</span>
                        @elseif($row->type === 'select_multiple')
                          <span>select_multiple</span>
                        @elseif($row->type === 'select_dropdown' && property_exists($options, 'options'))
                          <span>select_dropdown</span>
                        @elseif($row->type === 'text')
                          @include('admin.elements.input-hidden-bread-access')
                          <div class="readmore">
                            {{ mb_strlen( $instance->{$row->field} ) > 200 ? mb_substr($instance->{$row->field}, 0, 200) . ' ...' : $instance->{$row->field} }}
                          </div>
                        @else
                          <span>{{ $instance->{$row->field} }}</span>
                        @endif
                      </td>
                    @endforeach
                    <td class="no-sort no-click" id="bread-actions">
                      @can('delete', $instance)
                        <a href="javascript:;"
                           class="btn btn-sm btn-danger pull-right edit"
                           data-id="{{ $instance->{$instance->getKeyName()} }}">
                          <i class="voyager-trash"></i> 删除
                        </a>
                      @endcan
                      @can('edit', $instance)
                        <a href="{{ route('admin.'.$dataType->slug.'.edit', $instance->{$instance->getKeyName()}) }}"
                           class="btn btn-sm btn-primary pull-right edit">
                          <i class="voyager-edit"></i> 编辑
                        </a>
                      @endcan
                      @can('edit', $instance)
                        <a href="{{ route('admin.'.$dataType->slug.'.builder', $instance->{$instance->getKeyName()}) }}"
                           class="btn btn-sm btn-success pull-right">
                          <i class="voyager-list"></i> 生成菜单项
                        </a>
                      @endcan
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
            </div>
            <div class="pull-left">
              <div role="status" class="show-res" aria-live="polite">
                {{
                  trans_choice('展示从 :from 到 :to 条结果，共 :all 条', $dataTypeContent->total(), [
                    'from' => $dataTypeContent->firstItem(),
                    'to' => $dataTypeContent->lastItem(),
                    'all' => $dataTypeContent->total()
                  ])
                }}
              </div>
            </div>
            <div class="pull-right">
              {{
                $dataTypeContent->appends([
                  's' => $search->value,
                  'filter' => $search->filter,
                  'key' => $search->key,
                  'order_by' => $orderBy,
                  'order_type' => $orderType
                ])->links()
              }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title"><i class="voyager-trash"></i> 您确定要删除它吗？</h4>
        </div>
        <div class="modal-footer">
          <form action="#" id="delete_form" method="POST">
            @method('DELETE')
            @csrf
            <input type="submit" class="btn btn-danger pull-right delete-confirm" value="是的,删除它!">
          </form>
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <script>
    $(document).ready(function () {
      $('#search-input select').select2({
        minimumResultsForSearch: Infinity
      });

      $('.select_all').on('click', function (e) {
        $('input[name="row_id"]').prop('checked', $(this).prop('checked'));
      });
    });

    var deleteFormAction;
    $('td').on('click', '.delete', function (e) {
      $('#delete_form')[0].action = '{{ route('admin.'.$dataType->slug.'.destroy', ['id' => '__id']) }}'.replace('__id', $(this).data('id'));
      $('#delete_modal').modal('show');
    });
  </script>
@stop
