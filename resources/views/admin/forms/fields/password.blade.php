@if(isset($dataTypeContent->{$row->field}))
  <br>
  <small>留空以保持不变</small>
@endif
<input type="password" class="form-control" name="{{ $row->field }}" value=""
       @if($row->required === 1 && !isset($dataTypeContent->{$row->field})) required @endif>
