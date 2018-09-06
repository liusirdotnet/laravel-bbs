@extends('admin.layouts.app')

@section('page_header')
  <div class="page-title">
    <i class="voyager-data"></i>
    {{ __('编辑 BREAD :table', ['table' => (isset($dataType->id) ? @$dataType->name : $table)]) }}
  </div>
@stop

@section('content')
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-md-12">
        <form method="post" role="form"
              action="@if(isset($dataType->id)){{ route('admin.breads.update', $dataType->id) }}@else{{ route('admin.breads.store') }}@endif">
          @if(isset($dataType->id))
            <input type="hidden" value="{{ $dataType->id }}" name="id">
            @method('PUT')
          @endif
          @csrf
          <div class="panel panel-primary panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title panel-icon"><i
                  class="voyager-bread"></i> {{ ucfirst($table) }} Bread 信息</h3>
              <div class="panel-actions">
                <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
              </div>
            </div>
            <div class="panel-body">
              <div class="row clearfix">
                <div class="col-md-6 form-group">
                  <label for="name">表名</label>
                  <input type="text" class="form-control" readonly name="name" placeholder="{{ __('generic_name') }}"
                         value="@if(isset($dataType->name)){{ $dataType->name }}@else{{ $table }}@endif">
                </div>
              </div>
              <div class="row clearfix">
                <div class="col-md-6 form-group">
                  <label for="display_name_singular">表名单数</label>
                  <input type="text" class="form-control"
                         name="display_name_singular"
                         id="display_name_singular"
                         placeholder="显示名称（单数）"
                         value="@if(isset($dataType->display_name_singular)){{ $dataType->display_name_singular }}@else{{ $display_name }}@endif">
                </div>
                <div class="col-md-6 form-group">
                  <label for="display_name_plural">表名复数</label>
                  <input type="text" class="form-control"
                         name="display_name_plural"
                         id="display_name_plural"
                         placeholder="显示名称（复数）"
                         value="@if(isset($dataType->display_name_plural)){{ $dataType->display_name_plural }}@else{{ $display_name_plural }}@endif">
                </div>
              </div>
              <div class="row clearfix">
                <div class="col-md-6 form-group">
                  <label for="slug">URL Slug</label>
                  <input type="text" class="form-control" name="slug"
                         placeholder="URL slug（例如：posts）"
                         value="@if(isset($dataType->slug)){{ $dataType->slug }}@else{{ $slug }}@endif">
                </div>
                <div class="col-md-6 form-group">
                  <label for="icon">Font Class Icon
                    <a href="{{ route('admin.compasses.index', [], false) }}#fonts"
                       target="_blank">Voyager Font Class</a>
                  </label>
                  <input type="text" class="form-control" name="icon"
                         placeholder="表使用的图标"
                         value="@if(isset($dataType->icon)){{ $dataType->icon }}@endif">
                </div>
              </div>
              <div class="row clearfix">
                <div class="col-md-6 form-group">
                  <label for="model_name">模型名称</label>
                  <span class="voyager-question"
                        aria-hidden="true"
                        data-toggle="tooltip"
                        data-placement="right"
                        title="{{ __('voyager::bread.model_name_ph') }}"></span>
                  <input type="text" class="form-control" name="model_name"
                         placeholder="模型名称"
                         value="@if(isset($dataType->model_name)){{ $dataType->model_name }}@else{{ $model_name }}@endif">
                </div>
                <div class="col-md-6 form-group">
                  <label for="controller">控制器名称</label>
                  <span class="voyager-question"
                        aria-hidden="true"
                        data-toggle="tooltip"
                        data-placement="right"
                        title="控制器名称"></span>
                  <input type="text" class="form-control" name="controller"
                         placeholder="控制器名称"
                         value="@if(isset($dataType->controller)){{ $dataType->controller }}@endif">
                </div>
              </div>
              <div class="row clearfix">
                <div class="col-md-6 form-group">
                  <label for="policy_name">策略名称</label>
                  <span class="voyager-question"
                        aria-hidden="true"
                        data-toggle="tooltip"
                        data-placement="right"
                        title="策略名称"></span>
                  <input type="text" class="form-control" name="policy_name"
                         placeholder="策略类名"
                         value="@if(isset($dataType->policy_name)){{ $dataType->policy_name }}@endif">
                </div>
                <div class="col-md-3 form-group">
                  <label for="generate_permissions">权限生成</label><br>
                  <?php $checked = (isset($dataType->generate_permissions) && $dataType->generate_permissions === 1) ? true : isset($generate_permissions, $generate_permissions); ?>
                  <input type="checkbox" name="generate_permissions" class="toggleswitch"
                         data-on="是" data-off="否" @if($checked) checked @endif>
                </div>
                <div class="col-md-3 form-group">
                  <label for="server_side">服务端分页</label><br>
                  <?php $checked = (isset($dataType->server_side) && $dataType->server_side === 1) ? true : isset($server_side, $server_side); ?>
                  <input type="checkbox" name="server_side" class="toggleswitch" data-on="是" data-off="否"
                         @if($checked) checked @endif>
                </div>
              </div>
              <div class="form-group">
                <label for="description">描述</label>
                <textarea class="form-control" name="description"
                          placeholder="描述">@if(isset($dataType->description)){{ $dataType->description }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="panel panel-primary panel-bordered">
            <div class="panel-heading">
              <h3 class="panel-title panel-icon"><i
                  class="voyager-window-list"></i> {{ __('编辑 :table 表的字段', ['table' => $table]) }}:</h3>
              <div class="panel-actions">
                <a class="panel-action voyager-angle-up" data-toggle="panel-collapse" aria-hidden="true"></a>
              </div>
            </div>
            <div class="panel-body">
              <div class="row fake-table-hd">
                <div class="col-xs-2">字段</div>
                <div class="col-xs-2">可见性</div>
                <div class="col-xs-2">输入类型</div>
                <div class="col-xs-2">显示名称</div>
                <div class="col-xs-4">可选项</div>
              </div>
              <div id="bread-items">
                <?php $r_order = 0 ?>

                @if (isset($options))
                  @foreach($options as $data)
                    <?php ++$r_order ?>
                    @if (isset($dataType->id))
                      <?php
                      $dataRow = \App\Models\DataRow::where('data_type_id', '=', $dataType->id)
                        ->where('field', '=', $data['field'])->first();
                      ?>
                    @endif

                    <div class="row row-dd">
                      <div class="col-xs-2">
                        <h4><strong>{{ $data['field'] }}</strong></h4>
                        <strong>类型：</strong> <span>{{ $data['type'] }}</span><br/>
                        <strong>索引：</strong> <span>{{ $data['key'] }}</span><br/>
                        <strong>非空：</strong>
                        @if($data['null'] === 'NO')
                          <span>是</span>
                          <input type="hidden" value="1" name="field_required_{{ $data['field'] }}" checked="checked">
                        @else
                          <span>否</span>
                          <input type="hidden" value="0" name="field_required_{{ $data['field'] }}">
                        @endif
                        <div class="handler voyager-handle"></div>
                        <input class="row_order" type="hidden" name="field_order_{{ $data['field'] }}"
                               value="@if(isset($dataRow->order)){{ $dataRow->order }}@else{{ $r_order }}@endif">
                      </div>
                      <div class="col-xs-2">
                        <input type="checkbox" id="field_access_{{ $data['field'] }}"
                               name="field_access_{{ $data['field'] }}"
                        @if(isset($dataRow->access) && $dataRow->access)
                          {{ 'checked="checked"' }}
                          @elseif($data['key'] === 'PRI')
                          @elseif($data['type'] === 'timestamp' && $data['field'] === 'updated_at')
                          @elseif(!isset($dataRow->access))
                          {{ 'checked="checked"' }}
                          @endif>
                        <label for="field_access_{{ $data['field'] }}">访问</label><br/>
                        <input type="checkbox"
                               id="field_read_{{ $data['field'] }}"
                               name="field_read_{{ $data['field'] }}" @if(isset($dataRow->read) && $dataRow->read){{ 'checked="checked"' }}@elseif($data['key'] === 'PRI')@elseif($data['type'] === 'timestamp' && $data['field'] === 'updated_at')@elseif(!isset($dataRow->read)){{ 'checked="checked"' }}@endif>
                        <label for="field_read_{{ $data['field'] }}">读取</label><br/>
                        <input type="checkbox"
                               id="field_edit_{{ $data['field'] }}"
                               name="field_edit_{{ $data['field'] }}" @if(isset($dataRow->edit) && $dataRow->edit){{ 'checked="checked"' }}@elseif($data['key'] === 'PRI')@elseif($data['type'] === 'timestamp' && $data['field'] === 'updated_at')@elseif(!isset($dataRow->edit)){{ 'checked="checked"' }}@endif>
                        <label for="field_edit_{{ $data['field'] }}">编辑</label><br/>
                        <input type="checkbox"
                               id="field_add_{{ $data['field'] }}"
                               name="field_add_{{ $data['field'] }}" @if(isset($dataRow->add) && $dataRow->add){{ 'checked="checked"' }}@elseif($data['key'] === 'PRI')@elseif($data['type'] === 'timestamp' && $data['field'] === 'created_at')@elseif($data['type'] === 'timestamp' && $data['field'] === 'updated_at')@elseif(!isset($dataRow->add)){{ 'checked="checked"' }}@endif>
                        <label for="field_add_{{ $data['field'] }}">添加</label><br/>
                        <input type="checkbox"
                               id="field_delete_{{ $data['field'] }}"
                               name="field_delete_{{ $data['field'] }}" @if(isset($dataRow->delete) && $dataRow->delete){{ 'checked="checked"' }}@elseif($data['key'] === 'PRI')@elseif($data['type'] === 'timestamp' && $data['field'] === 'updated_at')@elseif(!isset($dataRow->delete)){{ 'checked="checked"' }}@endif>
                        <label for="field_delete_{{ $data['field'] }}">删除</label><br/>
                      </div>
                      <div class="col-xs-2">
                        <input type="hidden" name="field_{{ $data['field'] }}" value="{{ $data['field'] }}">
                        @if ($data['type'] === 'timestamp')
                          <p>时间戳</p>
                          <input type="hidden" value="timestamp" name="field_input_type_{{ $data['field'] }}">
                        @else
                          <select name="field_input_type_{{ $data['field'] }}">
                            @foreach (\App\Support\Facades\Admin::formFields() as $formField)
                              @php
                                if ((isset($dataRow->type) && $dataRow->type === $formField->getCodename())
                                    || (!isset($dataRow->type) && $formField->getCodename() === 'text')
                                ) {
                                    $selected = true;
                                } else {
                                    $selected = false;
                                }
                              @endphp
                              <option value="{{ $formField->getCodename() }}" {{ $selected ? 'selected' : '' }}>
                                {{ $formField->getName() }}
                              </option>
                            @endforeach
                          </select>
                        @endif
                      </div>
                      <div class="col-xs-2">
                        <input type="text" class="form-control" name="field_display_name_{{ $data['field'] }}"
                               value="@if(isset($dataRow->display_name)){{ $dataRow->display_name }}@else{{ ucwords(str_replace('_', ' ', $data['field'])) }}@endif">
                      </div>
                      <div class="col-xs-4">
                        <div class="alert alert-danger validation-error">无效的 JSON</div>
                        <textarea id="json-input-{{ $data['field'] }}" class="resizable-editor" data-editor="json"
                                  name="field_details_{{ $data['field'] }}">@if(isset($dataRow->details)){{ $dataRow->details }}@endif</textarea>
                      </div>
                    </div>
                  @endforeach
                @endif

                @if(isset($dataTypeRelationships))
                  @foreach($dataTypeRelationships as $relationship)
                    @include('admin.tools.bread.relationship-partial', $relationship)
                  @endforeach
                @endif

              </div>
            </div>
            <div class="panel-footer">
              <div class="btn btn-new-relationship">
                <i class="voyager-heart"></i> <span>创建关系</span>
              </div>
            </div>
          </div>
          <button type="submit" class="btn pull-right btn-primary">保存</button>
        </form>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
  <script>
    window.invalidEditors = [];
    let validationAlerts = $('.validation-error');
    validationAlerts.hide();

    $(function () {
      /**
       * Reorder items
       */
      reOrderItems();

      $('#bread-items').disableSelection();
      $('[data-toggle="tooltip"]').tooltip();
      $('.toggleswitch').bootstrapToggle();

      $('textarea[data-editor]').each(function () {
        let textarea = $(this),
          mode = textarea.data('editor'),
          editDiv = $('<div>').insertBefore(textarea),
          editor = ace.edit(editDiv[0]),
          _session = editor.getSession(),
          valid = false;
        textarea.hide();

        // Validate JSON
        _session.on("changeAnnotation", function () {
          valid = _session.getAnnotations().length ? false : true;
          if (!valid) {
            if (window.invalidEditors.indexOf(textarea.attr('id')) < 0) {
              window.invalidEditors.push(textarea.attr('id'));
            }
          } else {
            for (var i = window.invalidEditors.length - 1; i >= 0; i--) {
              if (window.invalidEditors[i] === textarea.attr('id')) {
                window.invalidEditors.splice(i, 1);
              }
            }
          }
        });

        // Use workers only when needed
        editor.on('focus', function () {
          _session.setUseWorker(true);
        });
        editor.on('blur', function () {
          if (valid) {
            textarea.siblings('.validation-error').hide();
            _session.setUseWorker(false);
          } else {
            textarea.siblings('.validation-error').show();
          }
        });

        _session.setUseWorker(false);

        editor.setAutoScrollEditorIntoView(true);
        editor.$blockScrolling = Infinity;
        editor.setOption("maxLines", 30);
        editor.setOption("minLines", 4);
        editor.setOption("showLineNumbers", false);
        editor.setTheme("ace/theme/github");
        _session.setMode("ace/mode/json");
        if (textarea.val()) {
          _session.setValue(JSON.stringify(JSON.parse(textarea.val()), null, 4));
        }

        _session.setMode("ace/mode/" + mode);

        // copy back to textarea on form submit...
        textarea.closest('form').on('submit', function (ev) {
          if (window.invalidEditors.length) {
            ev.preventDefault();
            ev.stopPropagation();
            validationAlerts.hide();
            for (let i = window.invalidEditors.length - 1; i >= 0; i--) {
              $('#' + window.invalidEditors[i]).siblings('.validation-error').show();
            }
            toastr.error('{{ __('voyager::json.invalid_message') }}', '{{ __('voyager::json.validation_errors') }}', {
              "preventDuplicates": true,
              "preventOpenDuplicates": true
            });
          } else {
            if (_session.getValue()) {
              // uglify JSON object and update textarea for submit purposes
              textarea.val(JSON.stringify(JSON.parse(_session.getValue())));
            } else {
              textarea.val('');
            }
          }
        });
      });

    });

    function reOrderItems() {
      $('#bread-items').sortable({
        handle: '.handler',
        update: function (e, ui) {
          var _rows = $('#bread-items').find('.row_order'),
            _size = _rows.length;

          for (var i = 0; i < _size; i++) {
            $(_rows[i]).val(i + 1);
          }
        },
        create: function (event, ui) {
          sort();
        }
      });
    }

    function sort() {
      let sortableList = $('#bread-items');
      let listitems = $('div.row.row-dd', sortableList);

      listitems.sort(function (a, b) {
        return (parseInt($(a).find('.row_order').val()) > parseInt($(b).find('.row_order').val())) ? 1 : -1;
      });
      sortableList.append(listitems);

    }

    /********** Relationship functionality **********/
    $(function () {
      $('.rowDrop').each(function () {
        populateRowsFromTable($(this));
      });

      $('.relationship_type').change(function () {
        if ($(this).val() === 'belongsTo') {
          $(this).parent().parent().find('.relationshipField').show();
          $(this).parent().parent().find('.relationshipPivot').hide();
          $(this).parent().parent().find('.relationship_key').show();
          $(this).parent().parent().find('.relationship_taggable').hide();
          $(this).parent().parent().find('.hasOneMany').removeClass('flexed');
          $(this).parent().parent().find('.belongsTo').addClass('flexed');
        } else if ($(this).val() === 'hasOne' || $(this).val() === 'hasMany') {
          $(this).parent().parent().find('.relationshipField').show();
          $(this).parent().parent().find('.relationshipPivot').hide();
          $(this).parent().parent().find('.relationship_key').hide();
          $(this).parent().parent().find('.relationship_taggable').hide();
          $(this).parent().parent().find('.hasOneMany').addClass('flexed');
          $(this).parent().parent().find('.belongsTo').removeClass('flexed');
        } else {
          $(this).parent().parent().find('.relationshipField').hide();
          $(this).parent().parent().find('.relationshipPivot').css('display', 'flex');
          $(this).parent().parent().find('.relationship_key').hide();
          $(this).parent().parent().find('.relationship_taggable').show();
        }
      });

      $('.btn-new-relationship').click(function () {
        $('#new_relationship_modal').modal('show');
      });

      relationshipTextDataBinding();

      $('.relationship_table').on('change', function () {
        let tbl_selected = $(this).val();
        let rowDropDowns = $(this).parent().parent().find('.rowDrop');
        $(this).parent().parent().find('.rowDrop').each(function () {
          console.log('1');
          $(this).data('table', tbl_selected);
          populateRowsFromTable($(this));
        });
      });

      $('.voyager-relationship-details-btn').click(function () {
        $(this).toggleClass('open');
        if ($(this).hasClass('open')) {
          $(this).parent().parent().find('.voyager-relationship-details').slideDown();
        } else {
          $(this).parent().parent().find('.voyager-relationship-details').slideUp();
        }
      });

    });

    function populateRowsFromTable(dropdown) {
      let tbl = dropdown.data('table');
      let selected_value = dropdown.data('selected');
      if (tbl.length !== 0) {
        $.get('{{ route('admin.databases.index') }}/' + tbl, function (data) {
          $(dropdown).empty();
          for (let option in data) {
            $('<option/>', {
              value: option,
              html: option
            }).appendTo($(dropdown));
          }

          if ($(dropdown).find('option[value="' + selected_value + '"]').length > 0) {
            $(dropdown).val(selected_value);
          }
        });
      }
    }

    function relationshipTextDataBinding() {
      $('.relationship_display_name').bind('input', function () {
        $(this).parent().parent().parent().find('.label_relationship p').text($(this).val());
      });
      $('.relationship_table').on('change', function () {
        var tbl_selected_text = $(this).find('option:selected').text();
        $(this).parent().parent().find('.label_table_name').text(tbl_selected_text);
      });
      $('.relationship_table').each(function () {
        var tbl_selected_text = $(this).find('option:selected').text();
        $(this).parent().parent().find('.label_table_name').text(tbl_selected_text);
      });
    }
  </script>
@stop
