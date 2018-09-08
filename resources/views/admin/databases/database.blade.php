@extends('admin.layouts.app')

@if ($db->action === 'update')
  @section('page_title', __('编辑表', ['table' => $db->table->name]))
@else
  @section('page_title', __('创建表'))
@endif

@section('page_header')
  <h1 class="page-title">
    <i class="voyager-data"></i>
    @if ($db->action === 'update')
      {{ __('编辑表', ['table' => $db->table->name]) }}
    @else
      {{ __('创建表') }}
    @endif
  </h1>
  <?php $table = $dataType->name ?? null; ?>
@stop

@section('content')
  <div class="page-content container-fluid">
    <div class="row">
      <div class="col-md-12" id="dbManager">
        <form @submit.prevent="stringifyTable" @keydown.enter.prevent action="{{ $db->formAction }}"
              method="POST" rel="form"
        >
          @if ($db->action === 'update')@method('PUT')@endif
          @csrf
          <database-table-editor :table="table"></database-table-editor>
          <input type="hidden" name="table" :value="tableJson">
        </form>
      </div>
    </div>
  </div>
@stop

@section('scripts')
  @include('admin.databases.components.database-table-editor')
  <script>
    new Vue({
      el: "#dbManager",
      data: {
        table: {},
        originalTable: {!! $db->table->toJson() !!},
        oldTable: {!! $db->oldTable !!},
        tableJson: ''
      },
      created() {
        if (this.oldTable) {
          this.table = this.oldTable;
        } else {
          this.table = JSON.parse(JSON.stringify(this.originalTable));
        }
      },
      methods: {
        stringifyTable() {
          this.tableJson = JSON.stringify(this.table);
          this.$nextTick(() => this.$refs.form.submit());
        }
      }
    });
  </script>
@show
