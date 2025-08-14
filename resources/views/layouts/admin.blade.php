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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.4/css/buttons.dataTables.min.css">
@yield('css')
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
@yield('js')
@stop