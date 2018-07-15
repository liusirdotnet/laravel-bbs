<ul class="nav navbar-nav">
  @foreach ($items as $item)
    @php
      $listItemClass = [];
      $styles = null;
      $linkAttributes = null;
      $transItem = $item;

      $href = $item->link();

      // Current page.
      if (url($href) === url()->current()) {
          $listItemClass[] = 'active';
      }

      $permission = '';
      $hasChildren = false;

      // With Children Attributes.
      if (! $item->children->isEmpty()) {
          foreach($item->children as $child) {
              $hasChildren = $hasChildren || Auth::user()->can('access', $child);

              if (url($child->link()) === url()->current()) {
                  $listItemClass[] = 'active';
              }
          }
          if (! $hasChildren) {
              continue;
          }

          $linkAttributes = 'href="#' . $transItem->id .'-dropdown-element" data-toggle="collapse" aria-expanded="'. (in_array('active', $listItemClass, true) ? 'true' : 'false').'"';
          $listItemClass[] = 'dropdown';
      } else {
          $linkAttributes =  'href="' . url($href) .'"';

          if (! Auth::user()->can('access', $item)) {
              continue;
          }
      }
    @endphp

    <li class="{{ implode(' ', $listItemClass) }}">
      <a {!! $linkAttributes !!} target="{{ $item->target }}"
         style="color:{{ (isset($item->color) && $item->color !== '#000000' ? $item->color : '') }}">
        <span class="icon {{ $item->icon_class }}"></span>
        <span class="title">{{ $transItem->title }}</span>
      </a>
      @if($hasChildren)
        <div id="{{ $transItem->id }}-dropdown-element"
             class="panel-collapse collapse {{ (in_array('active', $listItemClass, true) ? 'in' : '') }}">
          <div class="panel-body">
            @include('admin.menus.menu', ['items' => $item->children, 'options' => $options, 'innerLoop' => true])
          </div>
        </div>
      @endif
    </li>
  @endforeach
</ul>
