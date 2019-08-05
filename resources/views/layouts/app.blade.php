<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'GSIS') }}</title>
    <link rel="shortcut icon" href="/icon.png">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.css"/> --}}
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/datatables/datatables.css')}}"/>

    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script type="text/java/script" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/r-2.2.2/sc-2.0.0/sl-1.3.0/datatables.js"></script> --}}
    <script type="text/javascript" src="{{asset('plugins/datatables/datatables.js')}}"></script>

</head>
<body>
    <div id="app">
        @include('includes.navbar')
        <div class="container pageContentWrapper" style="padding-top: 1rem">
            @include('includes.messages')
            @yield('content')
            @yield('modal')
        </div>
    </div>

    @stack('scripts')

    @include('includes.footer')
</body>

</html>
