@extends('admin.layouts.app')

@section('content')
  <div class="page-content">
    @include('admin.components.alert')
    @include('admin.components.group')
    <div class="analytics-container">
      @if (isset($google_analytics_client_id) && !empty($google_analytics_client_id))
        {{-- Google Analytics Embed --}}
        <div id="embed-api-auth-container"></div>
      @else
        <p style="border-radius:4px; padding:20px; background:#fff; margin:0; color:#999; text-align:center;">
          没有客户端 ID
          <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>
        </p>
      @endif

      <div class="Dashboard Dashboard--full" id="analytics-dashboard">
        <header class="Dashboard-header">
          <ul class="FlexGrid">
            <li class="FlexGrid-item">
              <div class="Titles">
                <h1 class="Titles-main" id="view-name">选择查看视图</h1>
                <div class="Titles-sub">复合图表</div>
              </div>
            </li>
            <li class="FlexGrid-item FlexGrid-item--fixed">
              <div id="active-users-container"></div>
            </li>
          </ul>
          <div id="view-selector-container"></div>
        </header>

        <ul class="FlexGrid FlexGrid--halves">
          <li class="FlexGrid-item">
            <div class="Chartjs">
              <header class="Titles">
                <h1 class="Titles-main">本周 VS 上周</h1>
                <div class="Titles-sub">以用户</div>
              </header>
              <figure class="Chartjs-figure" id="chart-1-container"></figure>
              <ol class="Chartjs-legend" id="legend-1-container"></ol>
            </div>
          </li>
          <li class="FlexGrid-item">
            <div class="Chartjs">
              <header class="Titles">
                <h1 class="Titles-main">本周 VS 上周</h1>
                <div class="Titles-sub">以用户</div>
              </header>
              <figure class="Chartjs-figure" id="chart-2-container"></figure>
              <ol class="Chartjs-legend" id="legend-2-container"></ol>
            </div>
          </li>
          <li class="FlexGrid-item">
            <div class="Chartjs">
              <header class="Titles">
                <h1 class="Titles-main">使用最多的浏览器</h1>
                <div class="Titles-sub">以 PV</div>
              </header>
              <figure class="Chartjs-figure" id="chart-3-container"></figure>
              <ol class="Chartjs-legend" id="legend-3-container"></ol>
            </div>
          </li>
          <li class="FlexGrid-item">
            <div class="Chartjs">
              <header class="Titles">
                <h1 class="Titles-main">访问量最高的国家</h1>
                <div class="Titles-sub">以 Session</div>
              </header>
              <figure class="Chartjs-figure" id="chart-4-container"></figure>
              <ol class="Chartjs-legend" id="legend-4-container"></ol>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
@stop
