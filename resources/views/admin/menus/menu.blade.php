@extends('admin.layouts.app')

@section('page_header')
  <h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i>
    {{ ($dataTypeContent->getKey() !== null ? '编辑' : '添加') . ' ' . $dataType->display_name_singular }}
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

              @php
                $dataTypeRows = $dataType->{($dataTypeContent->getKey() !== null ? 'editRows' : 'addRows' )};
              @endphp

              @foreach($dataTypeRows as $row)
                @php
                  $options = json_decode($row->details);
                  $display_options = $options->display ?? null;
                @endphp
                @if($options && isset($options->legend, $options->legend->text))
                  <legend class="text-{{$options->legend->align or 'center'}}"
                          style="background-color: {{$options->legend->bgcolor or '#f0f0f0'}};padding: 5px;">{{$options->legend->text}}</legend>
                @endif
                @if($options && isset($options->formfields_custom))
                  @include('admin.forms.fields.custom.' . $options->formfields_custom)
                @else
                  <div
                      class="form-group @if($row->type === 'hidden') hidden @endif col-md-{{ $display_options->width or 12 }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                    {{ $row->slugify }}
                    <label for="name">{{ $row->display_name }}</label>
                    @include('admin.elements.input-hidden-bread-access')

                    @if($row->type === 'relationship')
                      @include('admin.forms.fields.relationship')
                    @else
                      {!! app('admin')->formField($row, $dataType, $dataTypeContent) !!}
                    @endif

                    @foreach (app('admin')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                      {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                    @endforeach
                  </div>
                @endif
              @endforeach
            </div>
            <div class="panel-footer">
              <button type="submit" class="btn btn-primary">保存</button>
            </div>
          </form>
          <iframe id="form_target" name="form_target" style="display:none"></iframe>
          <form id="my_form" action="#" target="form_target" method="post"
                enctype="multipart/form-data" style="width:0;height:0;overflow:hidden">
            <input name="image" id="upload_file" type="file"
                   onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade modal-danger" id="confirm_delete_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="voyager-warning"></i> 您确定吗？</h4>
        </div>

        <div class="modal-body">
          <h4>你确定要删除吗 '<span class="confirm_delete_name"></span>'</h4>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
          <button type="button" class="btn btn-danger" id="confirm_delete">是的，删除它！</button>
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

      $('#confirm_delete').on('click', function () {
        $.post('', params, function (response) {
          if (response
            && response.data
            && response.data.status
            && response.data.status === 200) {

            toastr.success(response.data.message);
            $image.parent().fadeOut(300, function () {
              $(this).remove();
            })
          } else {
            toastr.error("Error removing image.");
          }
        });

        $('#confirm_delete_modal').modal('hide');
      });
      $('[data-toggle="tooltip"]').tooltip();
    });
  </script>
@stop
