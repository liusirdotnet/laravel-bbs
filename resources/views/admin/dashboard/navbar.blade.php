<nav class="navbar navbar-default navbar-fixed-top navbar-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button class="hamburger btn-link">
        <span class="hamburger-inner"></span>
      </button>
      <ol class="breadcrumb hidden-xs">
        <li class="active"><i class="voyager-boat"></i> 仪表盘</li>
      </ol>
    </div>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown profile">
        <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button" aria-expanded="false"><img
              src="{{ asset('backend/images/captain-avatar.png') }}" class="profile-img">
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-animated">
          <li class="profile-img">
            <img src="{{ asset('backend/images/captain-avatar.png') }}" class="profile-img">
            <div class="profile-body">
              <h5>{{ ucwords(Auth::user()->name) }}</h5>
              <h6>{{ Auth::user()->email }}</h6>
            </div>
          </li>
          <li class="divider"></li>
          <li class="class-full-of-rum">
            <a href="{{ route('admin.profile') }}">
              <i class="voyager-person"></i>
              个人简介
            </a>
          </li>
          <li>
            <a href="/" target="_blank">
              <i class="voyager-home"></i>
              前台主页
            </a>
          </li>
          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-danger btn-block">
                <i class="voyager-power"></i>
                退出登录
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
