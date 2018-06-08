@extends('layouts.app')

@section('title', $user->name . ' 的个人中心')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-lg col-lg-3 col-md-3">
        <div class="card">
          <div class="card-body">
            <img class="card-img-top"
                 src="https://lccdn.phphub.org/uploads/avatars/24031_1523004832.jpg?imageView2/1/w/100/h/100"
                 alt="Card image cap">
            <hr>
            <h5 class="card-title">个人简介</h5>
            <p class="card-text">{{ $user->introduction }}</p>
            <hr>
            <h5><strong>注册于</strong></h5>
            <p>{{ $user->created_at->diffForHumans() }}</p>
          </div>
        </div>
      </div>
      <div class="col-lg col-lg-9 col-md-9 col-sm-12">
        <div class="card">
          <div class="card-body">
            {{ $user->name }}
            <small>{{ $user->email }}</small>
          </div>
        </div>

        <div class="card mx-auto">
          <div class="card-body">
            暂无数据 ~_~
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
