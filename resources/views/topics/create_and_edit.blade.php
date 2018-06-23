@extends('layouts.app')

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/simditor.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('js/module.js') }}"></script>
  <script src="{{ asset('js/hotkeys.js') }}"></script>
  <script src="{{ asset('js/uploader.js') }}"></script>
  <script src="{{ asset('js/simditor.js') }}"></script>
  <script>
    (function ($) {
      var editor = new Simditor({
        textarea: $('#editor')
      });
    })(jQuery);
  </script>
@endsection

@section('content')
  <div class="container">
    <div class="col-md-10 offset-1">
      <div class="card">
        <div class="card-header">
          <h2>
            <i class="fa fa-edit"></i> 话题 /
            @if($topic->id)
              编辑话题
            @else
              新建话题
            @endif
          </h2>
        </div>

        @include('common.error')

        <div class="card-body">
          @php
            $action = $topic->id ? route('topics.update', $topic->id): route('topics.store');
          @endphp
          <form action="{{ $action }}" method="POST" accept-charset="UTF-8">
            @csrf
            @if ($topic->id)
              @method('put')
            @endif

            <div class="form-group">
              <label for="title-field">标题</label>
              <input class="form-control" type="text" name="title" id="title-field"
                     value="{{ old('title', $topic->title ) }}"
                     placeholder="请填写标题">
            </div>
            <div class="form-group">
              <label for="category-id-field">分类</label>
              <select class="form-control" name="category_id" id="category-id-field">
                <option value="" hidden disabled selected>请选择分类</option>
                @foreach ($categories as $value)
                  <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="editor">内容</label>
              <textarea class="form-control" name="body" id="editor" rows="3"
                        placeholder="请填入至少三个字符的内容">{{ old('body', $topic->body ) }}</textarea>
            </div>
            <div class="form-group row mb-0">
              <div class="col-md-6">
                <button type="submit" class="btn btn-outline-primary">
                  <i class="fa fa-save"></i> 保存
                </button>
                <a class="btn btn-outline-primary" href="{{ route('topics.index') }}">
                  <i class="fa fa-backward"></i> 返回
                </a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
