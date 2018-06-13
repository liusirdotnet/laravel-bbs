<nav class="navbar navbar-expand-md navbar-light fixed-top lbs-navbar">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      {{ config('app.name', 'Laravel-BBS') }}
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="{{ url('/') }}"> 首页<span class="sr-only"></span></a>
        </li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav ml-auto lbs-nav-right">
        <!-- Authentication Links -->
        @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">登录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">注册</a>
          </li>
        @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
               aria-haspopup="true" aria-expanded="false" v-pre>
              <span>
                <img class="rounded-circle"
                     src="{{ Auth::user()->avatar }}" width="30px" height="30px" alt="头像">
              </span>
              {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">编辑资料</a>
              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                退出登录
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>
