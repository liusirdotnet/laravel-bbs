@if(isset($options->model, $options->type))
  @if(class_exists($options->model))
    @php $relationshipField = $row->field; @endphp

    @if($options->type === 'belongsTo')

      @if(isset($view) && ($view === 'access' || $view === 'read'))
        @php
          $relationship = $instance ?? $dataTypeContent;
          $model = app($options->model);
          if (method_exists($model, 'getRelationship')) {
            $query = $model::getRelationship($relationship->{$options->column});
          } else {
            $query = $model::find($relationship->{$options->column});
          }
        @endphp
        @if(isset($query))
          <p>{{ $query->{$options->label} }}</p>
        @else
          <p>暂无</p>
        @endif
      @else
        <select class="form-control select2" name="{{ $options->column }}">
          @php
            $model = app($options->model);
            $query = $model::all();
          @endphp
          @if($row->required === 0)
            <option value="">暂无</option>
          @endif

          @foreach($query as $relationship)
            <option value="{{ $relationship->{$options->key} }}" @if($dataTypeContent->{$options->column} === $relationship->{$options->key}){{ 'selected="selected"' }}@endif>{{ $relationship->{$options->label} }}</option>
          @endforeach
        </select>
      @endif

    @elseif($options->type === 'hasOne')

      @php
        $relationship = $instance ?? $dataTypeContent;
        $model = app($options->model);
        $query = $model::where($options->column, '=', $relationship->id)->first();
      @endphp
      @if(isset($query))
        <p>{{ $query->{$options->label} }}</p>
      @else
        <p>暂无</p>
      @endif

    @elseif($options->type === 'hasMany')

      @if(isset($view) && ($view === 'access' || $view === 'read'))
        @php
          // $relationship = $instance ?? $dataTypeContent;
          // $model = app($options->model);
          // $selected_values = $model::where($options->column, '=', $relationship->id)->pluck($options->label)->all();
          $selected_values = $instance->{$options->method}->pluck('display_name')->toArray();
        @endphp

        @if($view === 'access')
          @php
            $string_values = implode(', ', $selected_values);
            if(strlen($string_values) > 25){ $string_values = substr($string_values, 0, 25) . '...'; }
          @endphp
          @if(empty($selected_values))
            <p>暂无</p>
          @else
            <p>{{ $string_values }}</p>
          @endif
        @else
          @if(empty($selected_values))
            <p>暂无</p>
          @else
            <ul>
              @foreach($selected_values as $selected_value)
                <li>{{ $selected_value }}</li>
              @endforeach
            </ul>
          @endif
        @endif

      @else

        @php
          $model = app($options->model);
          $query = $model::where($options->column, '=', $dataTypeContent->id)->get();
        @endphp
        @if(isset($query))
          <ul>
            @foreach($query as $query_res)
              <li>{{ $query_res->{$options->label} }}</li>
            @endforeach
          </ul>
        @else
          <p>暂无</p>
        @endif

      @endif

    @elseif($options->type === 'belongsToMany')

      @if(isset($view) && ($view === 'access' || $view === 'read'))
        @php
          //$relationship = $instance ?? $dataTypeContent;
          // $selected_values = isset($relationship) ? $relationship->belongsToMany($options->model, $options->pivot_table)->pluck($options->label)->all() : [];
          $selected_values = $instance->{$options->method}->pluck('display_name')->toArray();
        @endphp
        @if($view === 'access')
          @php
            $string_values = implode(', ', $selected_values);
            if(strlen($string_values) > 25){ $string_values = substr($string_values, 0, 25) . '...'; }
          @endphp
          @if(empty($selected_values))
            <p>暂无</p>
          @else
            <p>{{ $string_values }}</p>
          @endif
        @else
          @if(empty($selected_values))
            <p>暂无</p>
          @else
            <ul>
              @foreach($selected_values as $selected_value)
                <li>{{ $selected_value }}</li>
              @endforeach
            </ul>
          @endif
        @endif
      @else
        <select
            class="form-control @if(isset($options->taggable) && $options->taggable === 'on') select2-taggable @else select2 @endif"
            name="{{ $relationshipField }}[]" multiple
            @if(isset($options->taggable) && $options->taggable === 'on')
            data-route="{{ route('voyager.'.str_slug($options->table).'.store') }}"
            data-label="{{$options->label}}"
            data-error-message="Sorry it appears there may have been a problem creating the record. Please make sure your table has defaults for other fields."
            @endif
        >

          @php
            $selected_values = isset($dataTypeContent) ? $dataTypeContent->belongsToMany($options->model, $options->pivot_table)->pluck($options->table.'.'.$options->key)->all() : array();
            $relationshipOptions = app($options->model)->all();
          @endphp
          @if($row->required === 0)
            <option value="">暂无</option>
          @endif

          @foreach($relationshipOptions as $relationshipOption)
            <option value="{{ $relationshipOption->{$options->key} }}" @if(in_array($relationshipOption->{$options->key}, $selected_values, true)){{ 'selected="selected"' }}@endif>{{ $relationshipOption->{$options->label} }}</option>
          @endforeach
        </select>
      @endif
    @endif
  @else
    Cannot make relationship because {{ $options->model }} does not exist.
  @endif
@endif
