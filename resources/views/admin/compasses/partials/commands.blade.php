@if($artisanOutput)
  <pre>
    <i class="close-output voyager-x">清除输出</i>
    <span class="art_out">Artisan Command Output:</span>{{ trim(trim($artisanOutput, '"')) }}
  </pre>
@endif

@foreach($commands as $command)
  <div class="command" data-command="{{ $command->name }}">
    <code>php artisan {{ $command->name }}</code>
    <small>{{ $command->description }}</small>
    <i class="voyager-terminal"></i>
    <form action="{{ route('admin.compasses.command') }}" class="cmd_form" method="POST">
      @csrf
      <input type="text" name="args" autofocus class="form-control" placeholder="添加参数">
      <input type="submit" class="btn btn-primary pull-right delete-confirm" value="运行命令">
      <input type="hidden" name="command" id="hidden_cmd" value="{{ $command->name }}">
    </form>
  </div>
@endforeach
