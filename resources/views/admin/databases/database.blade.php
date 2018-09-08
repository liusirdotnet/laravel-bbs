@extends('admin.layouts.app')

@if ($db->action === 'update')
  @section('page_title', __('编辑表', ['table' => $db->table->name]))
@else
  @section('page_title', __('创建表'))
@endif

@section('page_header')
  <h1 class="page-title">
    <i class="voyager-data"></i>
    @if ($db->action == 'update')
      {{ __('编辑表', ['table' => $db->table->name]) }}
    @else
      {{ __('创建表') }}
    @endif
  </h1>
@stop
