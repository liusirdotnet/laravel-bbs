@extends('admin.layouts.app')

@section('title', '添加角色')

@section('page_header')
  <h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i>
    {{ (isset($dataTypeContent->id) ? '编辑' : '添加') . $dataType->display_name_singular }}
  </h1>
@stop

@section('content')
  <div class="page-content container-fluid">
    @include('admin.components.alert')
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-bordered">
          <form class="form-edit-add" role="form"
                action="@if(isset($dataTypeContent->id)){{ route('admin.'.$dataType->slug.'.update', $dataTypeContent->id) }}@else{{ route('admin.'.$dataType->slug.'.store') }}@endif"
                method="POST" enctype="multipart/form-data">
            @if(isset($dataTypeContent->id))
              @method('PUT')
            @endif
            @csrf

            <div class="panel-body">
              @if (count($errors) > 0)
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              @foreach($dataType->addRows as $row)
                <div class="form-group">
                  <label for="name">{{ $row->display_name }}</label>
                  {!! Admin::formField($row, $dataType, $dataTypeContent) !!}
                </div>
              @endforeach

              <label for="permission">权限</label><br>
              <a href="#" class="permission-select-all">选择全部</a> / <a href="#" class="permission-deselect-all">反选全部</a>
              <ul class="permissions checkbox">
                  <?php
                  $role_permissions =
                      (isset($dataTypeContent)) ? $dataTypeContent->permissions->pluck('key')->toArray() : [];
                  ?>
                @foreach(App\Models\Permission::all()->groupBy('table_name') as $table => $permissions)
                  <li>
                    <input type="checkbox" id="{{$table}}" class="permission-group">
                    <label for="{{ $table }}">
                      <strong>{{ title_case(str_replace('_',' ', $table)) }}</strong>
                    </label>
                    <ul>
                      @foreach($permissions as $permission)
                        <li>
                          <input type="checkbox" id="permission-{{ $permission->id }}" name="permissions[]"
                                 class="the-permission" value="{{ $permission->id }}"
                                 @if(in_array($permission->key, $role_permissions, true)) checked @endif>
                          <label for="permission-{{$permission->id}}">
                            {{ title_case(str_replace('_', ' ', $permission->action)) }}
                          </label>
                        </li>
                      @endforeach
                    </ul>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="panel-footer">
              <button type="submit" class="btn btn-primary">保存</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <script>
    $('document').ready(function () {
      $('.toggleswitch').bootstrapToggle();

      $('.permission-group').on('change', function () {
        $(this).siblings('ul').find("input[type='checkbox']").prop('checked', this.checked);
      });

      $('.permission-select-all').on('click', function () {
        $('ul.permissions').find("input[type='checkbox']").prop('checked', true);
        return false;
      });

      $('.permission-deselect-all').on('click', function () {
        $('ul.permissions').find("input[type='checkbox']").prop('checked', false);
        return false;
      });

      function parentChecked() {
        $('.permission-group').each(function () {
          var allChecked = true;
          $(this).siblings('ul').find("input[type='checkbox']").each(function () {
            if (!this.checked) allChecked = false;
          });
          $(this).prop('checked', allChecked);
        });
      }

      parentChecked();

      $('.the-permission').on('change', function () {
        parentChecked();
      });
    });
  </script>
@stop
