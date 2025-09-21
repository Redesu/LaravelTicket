@extends('adminlte::page')

@section('title', config('adminlte.title', 'AdminLTE 3'))

@section('content_header')
@yield('content_header')
@stop

@section('content')
@if(isset($iFrameEnabled) && $iFrameEnabled)
    <div class="content-wrapper iframe-mode" data-widget="iframe" data-loading-screen="750">
        <div class="nav navbar navbar-expand-lg navbar-white navbar-light border-bottom p-0">
            <a class="nav-link bg-danger" href="#" data-widget="iframe-close">Close</a>
            <ul class="navbar-nav" role="tablist"></ul>
        </div>
        <div class="tab-content">
            <div class="tab-empty">
                <h2 class="display-4">No tab selected!</h2>
            </div>
            <div class="tab-loading">
                <div>
                    <h2 class="display-4">Tab is loading <i class="fa fa-sync fa-spin"></i></h2>
                </div>
            </div>
            @yield('content')
        </div>
    </div>
@else
    @yield('content')
@endif
@stop

@section('css')
@yield('css')
@stop

@section('js')
@yield('js')
@stop

@vite('resources/js/app.js')
@vite('resources/css/app.css')