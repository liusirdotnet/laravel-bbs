<div class="side-menu sidebar-inverse ps ps--theme_default ps--active-y">
  <nav class="navbar navbar-default" role="navigation">
    <div class="side-menu-container">
      <div class="navbar-header">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
          <div class="logo-icon-container">
            <img src="{{ asset('backend/images/logo-icon-light.png') }}" alt="Logo Icon">
          </div>
          <div class="title">Laravel</div>
        </a>
      </div>

      <div class="panel widget center bgimage"
           style="background-image:url({{ asset('backend/images/bg.jpg') }}); background-size: cover; background-position: 0px;">
        <div class="dimmer"></div>
        <div class="panel-content">
          <img src="{{ asset('backend/images/captain-avatar.png') }}" class="avatar"
               alt="User Avatar">
          <h4>{{ ucwords(Auth::user()->name) }}</h4>
          <p>{{ Auth::user()->email }}</p>
          <a href="{{ route('admin.profile') }}" class="btn btn-primary">个人简介</a>
          <div style="clear:both"></div>
        </div>
      </div>

    </div>

    {!! admin_menu('founder', 'admin_menu') !!}
  </nav>
</div>
