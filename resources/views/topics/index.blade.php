@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-plus"></i> 话题
          </div>
          <div class="card-body">
            <a class="btn btn-success" href="{{ route('topics.create') }}"><i
                  class="fa fa-plus"></i> Create</a>

            @if($topics->count())
              <table class="table table-condensed table-striped">
                <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Title</th>
                  <th>Body</th>
                  <th>User_id</th>
                  <th>Category_id</th>
                  <th>Reply_count</th>
                  <th>View_count</th>
                  <th>Last_reply_user_id</th>
                  <th>Order</th>
                  <th>Excerpt</th>
                  <th>Slug</th>
                  <th class="text-right">OPTIONS</th>
                </tr>
                </thead>

                <tbody>
                @foreach($topics as $topic)
                  <tr>
                    <td class="text-center"><strong>{{$topic->id}}</strong></td>

                    <td>{{$topic->title}}</td>
                    <td>{{$topic->body}}</td>
                    <td>{{$topic->user_id}}</td>
                    <td>{{$topic->category_id}}</td>
                    <td>{{$topic->reply_count}}</td>
                    <td>{{$topic->view_count}}</td>
                    <td>{{$topic->last_reply_user_id}}</td>
                    <td>{{$topic->order}}</td>
                    <td>{{$topic->excerpt}}</td>
                    <td>{{$topic->slug}}</td>

                    <td class="text-right">
                      <a class="btn btn-xs btn-primary" href="{{ route('topics.show', $topic->id) }}">
                        <i class="glyphicon glyphicon-eye-open"></i>
                      </a>

                      <a class="btn btn-xs btn-warning" href="{{ route('topics.edit', $topic->id) }}">
                        <i class="glyphicon glyphicon-edit"></i>
                      </a>

                      <form action="{{ route('topics.destroy', $topic->id) }}" method="POST" style="display: inline;"
                            onsubmit="return confirm('Delete? Are you sure?');">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="DELETE">

                        <button type="submit" class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                @endforeach
                </tbody>
              </table>
              {!! $topics->render() !!}
            @else
              <h3 class="text-center alert alert-info">Empty!</h3>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
