@extends('admin.layouts.app')

@section('styles')
  @include('admin.compasses.partials.style')
@stop

@section('page_header')
  <h1 class="page-title"><i class="voyager-compass"></i> 指南</h1>
@stop

@section('content')
  <div id="gradient_bg"></div>

  <div class="container-fluid">
    @include('admin.components.alert')
  </div>

  <div class="page-content compass container-fluid">
    <ul class="nav nav-tabs">
      <li @if(empty($active_tab) || (isset($active_tab) && $active_tab === 'resources')){!! 'class="active"' !!}@endif>
        <a data-toggle="tab" href="#resources">
          <i class="voyager-book"></i> 图标
        </a>
      </li>
      <li @if($active_tab === 'commands'){!! 'class="active"' !!}@endif>
        <a data-toggle="tab" href="#commands">
          <i class="voyager-terminal"></i> 命令
        </a>
      </li>
      <li @if($active_tab === 'logs'){!! 'class="active"' !!}@endif>
        <a data-toggle="tab" href="#logs">
          <i class="voyager-logbook"></i> 日志
        </a>
      </li>
    </ul>

    <div class="tab-content">
      <div id="resources"
           class="tab-pane fade in @if(empty($active_tab) || (isset($active_tab) && $active_tab === 'resources')){!! 'active' !!}@endif">
        <h3><i class="voyager-book"></i> 资源
          <small>快速找到合适的字体图标</small>
        </h3>

        <div class="collapsible">
          <div class="collapse-head" data-toggle="collapse" data-target="#links" aria-expanded="true"
               aria-controls="links">
            <h4>相关链接</h4>
            <i class="voyager-angle-down"></i>
            <i class="voyager-angle-up"></i>
          </div>
          <div class="collapse-content collapse in" id="links">
            <div class="row">
              <div class="col-md-4">
                <a href="https://laravelvoyager.com/docs" target="_blank" class="voyager-link"
                   style="background-image:url({{ asset('backend/images/compass/documentation.jpg') }})">
                  <span class="resource_label"><i class="voyager-documentation"></i> <span class="copy">文档</span></span>
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://laravelvoyager.com" target="_blank" class="voyager-link"
                   style="background-image:url({{ asset('backend/images/compass/voyager-home.jpg') }})">
                  <span class="resource_label"><i class="voyager-browser"></i> <span class="copy">主页</span></span>
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://larapack.io" target="_blank" class="voyager-link"
                   style="background-image:url({{ asset('backend/images/compass/hooks.jpg') }})">
                  <span class="resource_label"><i class="voyager-hook"></i> <span class="copy">钩子</span></span>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="collapsible">
          <div class="collapse-head" data-toggle="collapse" data-target="#fonts" aria-expanded="true"
               aria-controls="fonts">
            <h4>体段图标</h4>
            <i class="voyager-angle-down"></i>
            <i class="voyager-angle-up"></i>
          </div>

          <div class="collapse-content collapse in" id="fonts">
            @include('admin.compasses.partials.fonts')
          </div>
        </div>
      </div>

      <div id="commands" class="tab-pane fade in @if($active_tab === 'commands'){!! 'active' !!}@endif">
        <h3>
          <i class="voyager-terminal"></i> Artisan 命令
          <small>点击运行相关命令</small>
        </h3>
        <div id="command_lists">
          @include('admin.compasses.partials.commands')
        </div>
      </div>

      <div id="logs" class="tab-pane fade in @if($active_tab === 'logs'){!! 'active' !!}@endif">
        <div class="row">
          @include('admin.compasses.partials.logs')
        </div>
      </div>
    </div>

  </div>

@stop
@section('javascript')
  <script>
    $('document').ready(function () {
      $('.collapse-head').click(function () {
        var collapseContainer = $(this).parent();
        if (collapseContainer.find('.collapse-content').hasClass('in')) {
          collapseContainer.find('.voyager-angle-up').fadeOut('fast');
          collapseContainer.find('.voyager-angle-down').fadeIn('slow');
        } else {
          collapseContainer.find('.voyager-angle-down').fadeOut('fast');
          collapseContainer.find('.voyager-angle-up').fadeIn('slow');
        }
      });
    });
  </script>
  <!-- JS for commands -->
  <script>

    $(document).ready(function () {
      $('.command').click(function () {
        $(this).find('.cmd_form').slideDown();
        $(this).addClass('more_args');
        $(this).find('input[type="text"]').focus();
      });

      $('.close-output').click(function () {
        $('#commands pre').slideUp();
      });
    });

  </script>

  <!-- JS for logs -->
  <script>
    $(document).ready(function () {
      $('.table-container tr').on('click', function () {
        $('#' + $(this).data('display')).toggle();
      });
      $('#table-log').DataTable({
        "order": [1, 'desc'],
        "stateSave": true,
        "language": {!! json_encode(__('voyager::datatable')) !!},
        "stateSaveCallback": function (settings, data) {
          window.localStorage.setItem("datatable", JSON.stringify(data));
        },
        "stateLoadCallback": function (settings) {
          let data = JSON.parse(window.localStorage.getItem('datatable'));
          if (data) data.start = 0;
          return data;
        }
      });

      $('#delete-log, #delete-all-log').click(function () {
        return confirm('您确定吗？');
      });
    });
  </script>
@stop
