@extends('layouts.app')

@section('title', $topic->title)
@section('description', $topic->excerpt)

@section('content')
  <div class="container">
    <div class="row">
      <div class="col col-lg-3 col-md-3">
        <div class="card">
          <div class="card-body">
            <p class="card-text text-center">作者：{{ $topic->user->name }}</p>
            <hr>
            <img class="card-img-top img-thumbnail"
                 src="{{ $topic->user->avatar }}"
                 width="300px" height="300px"
                 alt="个人头像">
          </div>
        </div>
      </div>

      <div class="col col-lg-9 col-md-9">
        <div class="card">
          <div class="card-body">
            <h1 class="text-center">{{ $topic->title }}</h1>
            <div class="article-meta text-center">
              <i class="fa fa-clock-o"></i> {{ $topic->created_at->diffForHumans() }}
              ⋅&nbsp;
              <i class="fa fa-comments-o"></i> {{ $topic->reply_count }}
            </div>
            <div class="row topic-body">
              <div class="col-md-12">{!! $topic->body !!}</div>
              TopicsController</div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <a class="btn btn-outline-primary" href="{{ route('topics.index') }}">
                  <i class="fa fa-backward"></i> 返回</a>
                <a class="btn btn-outline-warning" href="{{ route('topics.edit', $topic->id) }}">
                  <i class="fa fa-edit"></i> 编辑
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop
