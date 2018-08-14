@extends('admin.layouts.app')

@section('title', isset($dataTypeContent->id) ? '编辑用户' : '添加用户')

@section('page_header')
  <h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i> {{ isset($dataTypeContent->id) ? '编辑' : '添加' }}
  </h1>
@stop

@section('content')
  <div class="page-content container-fluid">
    <form class="form-edit-add" role="form"
          action="{{ (isset($dataTypeContent->id)) ? route('admin.'.$dataType->slug.'.update', $dataTypeContent->id) : route('admin.'.$dataType->slug.'.store') }}"
          method="POST" enctype="multipart/form-data" autocomplete="off">
      @if(isset($dataTypeContent->id))
        @method('PUT')
      @endif
      @csrf

      <div class="row">
        <div class="col-md-8">
          <div class="panel panel-bordered">
            @if (count($errors) > 0)
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <div class="panel-body">
              <div class="form-group">
                <label for="name">用户名称</label>
                <input type="text" class="form-control" id="name" name="name"
                       placeholder="请输入用户名称"
                       value="@if(isset($dataTypeContent->name)){{ $dataTypeContent->name }}@endif">
              </div>

              <div class="form-group">
                <label for="email">邮箱地址</label>
                <input type="email" class="form-control" id="email" name="email"
                       placeholder="请输入邮箱地址"
                       value="@if(isset($dataTypeContent->email)){{ $dataTypeContent->email }}@endif">
              </div>

              <div class="form-group">
                <label for="password">用户密码</label>
                @if(isset($dataTypeContent->password))
                  <br>
                  <small>留空为不修改密码</small>
                @endif
                <input type="password" class="form-control" id="password" name="password" value=""
                       autocomplete="new-password">
              </div>

              @can('updateRoles', $dataTypeContent)
                <div class="form-group">
                  <label for="default_role">默认角色</label>
                  @php
                    $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                    $row = $dataTypeRows->where('field', 'role')->first();
                    $options = $row->details === null ?: json_decode($row->details);
                  @endphp
                  @include('admin.forms.fields.relationship')
                </div>
                <div class="form-group">
                  <label for="additional_roles">其它角色</label>
                  @include('admin.forms.fields.relationship')
                </div>
              @endcan
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="panel panel panel-bordered panel-warning">
            <div class="panel-body">
              <div class="form-group">
                @if(isset($dataTypeContent->avatar))
                  <img
                    src="{{ filter_var($dataTypeContent->avatar, FILTER_VALIDATE_URL) ? $dataTypeContent->avatar : Admin::getImage( $dataTypeContent->avatar ) }}"
                    style="width:200px;height:auto;clear:both;display:block;padding:2px;border:1px solid #ddd;margin-bottom:10px;">
                @endif
                <input type="file" data-name="avatar" name="avatar" value="上传头像">
              </div>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary pull-right save">保存</button>
    </form>

    <iframe id="form_target" name="form_target" style="display:none"></iframe>
    <form id="upload-form" action="{{ route('admin.users.upload') }}" target="form_target" method="post"
          enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
      @csrf
      <input name="image" id="upload_file" type="file" onchange="$('#upload-form').submit();this.value='';">
      <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
    </form>
  </div>
@stop

@section('scripts')
  <script>
    $('document').ready(function () {
      $('.toggleswitch').bootstrapToggle();
    });
  </script>
@stop
