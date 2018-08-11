@extends('admin.layouts.app')

@section('content')
  <div class="page-content">
    @include('admin.components.alert')
    @include('admin.components.group')
    <div class="analytics-container">
      @if (isset($google_analytics_client_id) && !empty($google_analytics_client_id))
        <div id="embed-api-auth-container"></div>
      @else
        <p style="border-radius:4px; padding:20px; background:#fff; margin:0; color:#999; text-align:center;">
          没有客户端 ID
          <a href="https://console.developers.google.com" target="_blank">https://console.developers.google.com</a>
        </p>
      @endif
    </div>
  </div>
@stop
