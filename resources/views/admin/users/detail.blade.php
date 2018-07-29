@extends('admin.layouts.app')

@section('page_title', '用户详情')

@section('page_header')
  <h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i> 查看 {{ ucfirst($dataType->display_name_singular) }} &nbsp;

    @can('edit', $dataTypeContent)
      <a href="{{ route('admin.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
        <span class="glyphicon glyphicon-pencil"></span>&nbsp; 编辑
      </a>
    @endcan

    @can('delete', $dataTypeContent)
      <a href="javascript:;" title="删除" class="btn btn-danger delete"
         data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">删除</span>
      </a>
    @endcan

    <a href="{{ route('admin.'.$dataType->slug.'.index') }}" class="btn btn-warning">
      <span class="glyphicon glyphicon-list"></span>&nbsp; 返回用户列表
    </a>
  </h1>
@stop

@section('content')
  <div class="page-content read container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-bordered" style="padding-bottom:5px;">
          @foreach($dataType->readRows as $row)
            @php
              $rowDetails = json_decode($row->details);
              if($rowDetails === null) {
                $rowDetails=new stdClass();
                $rowDetails->options=new stdClass();
              }
            @endphp

            <div class="panel-heading" style="border-bottom:0;">
              <h3 class="panel-title">{{ $row->display_name }}</h3>
            </div>

            <div class="panel-body" style="padding-top:0;">
              @if($row->type === 'image')
                <img class="img-responsive"
                     src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Admin::getImage($dataTypeContent->{$row->field}) }}">
              @elseif($row->type === 'multiple_images')
                @if(json_decode($dataTypeContent->{$row->field}))
                  @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                    <img class="img-responsive"
                         src="{{ filter_var($file, FILTER_VALIDATE_URL) ? $file : Admin::getImage($file) }}">
                  @endforeach
                @else
                  <img class="img-responsive"
                       src="{{ filter_var($dataTypeContent->{$row->field}, FILTER_VALIDATE_URL) ? $dataTypeContent->{$row->field} : Admin::getImage($dataTypeContent->{$row->field}) }}">
                @endif
              @elseif($row->type === 'relationship')
                @include('admin.forms.fields.relationship', ['view' => 'read', 'options' => $rowDetails])
              @elseif($row->type === 'select_dropdown' && property_exists($rowDetails, 'options') && !empty($rowDetails->options->{$dataTypeContent->{$row->field}}))
                    <?php echo $rowDetails->options->{$dataTypeContent->{$row->field}};?>
              @elseif($row->type === 'select_dropdown' && $dataTypeContent->{$row->field . '_page_slug'})
                <a href="{{ $dataTypeContent->{$row->field . '_page_slug'} }}">{{ $dataTypeContent->{$row->field}  }}</a>
              @elseif($row->type === 'select_multiple')
                @if(property_exists($rowDetails, 'relationship'))

                  @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                    @if($item->{$row->field . '_page_slug'})
                      <a href="{{ $item->{$row->field . '_page_slug'} }}">{{ $item->{$row->field}  }}</a>@if(!$loop->last)
                        , @endif
                    @else
                      {{ $item->{$row->field}  }}
                    @endif
                  @endforeach

                @elseif(property_exists($rowDetails, 'options'))
                  @foreach(json_decode($dataTypeContent->{$row->field}) as $item)
                    {{ $rowDetails->options->{$item} . (!$loop->last ? ', ' : '') }}
                  @endforeach
                @endif
              @elseif($row->type === 'date' || $row->type === 'timestamp')
                {{ $rowDetails && property_exists($rowDetails, 'format') ? \Carbon\Carbon::parse($dataTypeContent->{$row->field})->formatLocalized($rowDetails->format) : $dataTypeContent->{$row->field} }}
              @elseif($row->type === 'checkbox')
                @if($rowDetails && property_exists($rowDetails, 'on') && property_exists($rowDetails, 'off'))
                  @if($dataTypeContent->{$row->field})
                    <span class="label label-info">{{ $rowDetails->on }}</span>
                  @else
                    <span class="label label-primary">{{ $rowDetails->off }}</span>
                  @endif
                @else
                  {{ $dataTypeContent->{$row->field} }}
                @endif
              @elseif($row->type === 'color')
                <span class="badge badge-lg"
                      style="background-color: {{ $dataTypeContent->{$row->field} }}">{{ $dataTypeContent->{$row->field} }}</span>
              @elseif($row->type === 'coordinates')
                @include('admin.partials.coordinates')
              @elseif($row->type === 'rich_text_box')
                @include('admin.multilingual.input-hidden-bread-read')
                <p>{!! $dataTypeContent->{$row->field} !!}</p>
              @elseif($row->type === 'file')
                @if(json_decode($dataTypeContent->{$row->field}))
                  @foreach(json_decode($dataTypeContent->{$row->field}) as $file)
                    <a href="{{ Storage::disk(config('admin.storage.disk'))->url($file->download_link) ?: '' }}">
                      {{ $file->original_name ?: '' }}
                    </a>
                    <br/>
                  @endforeach
                @else
                  <a href="{{ Storage::disk(config('admin.storage.disk'))->url($row->field) ?: '' }}">
                    下载
                  </a>
                @endif
              @else
                @include('admin.elements.input-hidden-bread-access')
                <p>{{ $dataTypeContent->{$row->field} }}</p>
              @endif
            </div>

            @if(!$loop->last)
              <hr style="margin:0;">
            @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>
  {{-- Single delete modal --}}
  <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="关闭">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            <i class="voyager-trash"></i> 您确定要删除它吗？{{ strtolower($dataType->display_name_singular) }}?
          </h4>
        </div>
        <div class="modal-footer">
          <form action="{{ route('admin.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger pull-right delete-confirm"
                   value="是的，删除这些 {{ strtolower($dataType->display_name_singular) }}">
          </form>
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal">取消</button>
        </div>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <script>
    var deleteFormAction;
    $('.delete').on('click', function (e) {
      var form = $('#delete_form')[0];

      if (!deleteFormAction) { // Save form action initial value
        deleteFormAction = form.action;
      }

      form.action = deleteFormAction.match(/\/[0-9]+$/)
        ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
        : deleteFormAction + '/' + $(this).data('id');
      console.log(form.action);

      $('#delete_modal').modal('show');
    });
  </script>
@stop
