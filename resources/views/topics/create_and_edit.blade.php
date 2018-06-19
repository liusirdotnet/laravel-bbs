@extends('layouts.app')

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
            @if ($topic->id)
              <input type="hidden" name="_method" value="PUT">
            @endif

            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
              <label for="title-field">Title</label>
              <input class="form-control" type="text" name="title" id="title-field"
                     value="{{ old('title', $topic->title ) }}"/>
            </div>
            <div class="form-group">
              <label for="body-field">Body</label>
              <textarea name="body" id="body-field" class="form-control"
                        rows="3">{{ old('body', $topic->body ) }}</textarea>
            </div>
            <div class="form-group">
              <label for="category_id-field">Category_id</label>
              <input class="form-control" type="text" name="category_id" id="category_id-field"
                     value="{{ old('category_id', $topic->category_id ) }}"/>
            </div>
            <div class="form-group">
              <label for="excerpt-field">Excerpt</label>
              <textarea name="excerpt" id="excerpt-field" class="form-control"
                        rows="3">{{ old('excerpt', $topic->excerpt ) }}</textarea>
            </div>
            <div class="form-group">
              <label for="slug-field">Slug</label>
              <input class="form-control" type="text" name="slug" id="slug-field"
                     value="{{ old('slug', $topic->slug ) }}"/>
            </div>

            <div class="well well-sm">
              <button type="submit" class="btn btn-primary">Save</button>
              <a class="btn btn-link pull-right" href="{{ route('topics.index') }}"><i
                    class="glyphicon glyphicon-backward"></i> Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
