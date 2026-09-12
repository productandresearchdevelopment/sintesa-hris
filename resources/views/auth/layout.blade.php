@extends('headers.head')

@section('body')
    <link rel="stylesheet" href="{{ asset('templates/auth/css/style.css') }}">
    <div class="d-flex justify-content-center align-items-center vh-100 p-4">
        <div class="main-container text-center p-5 rounded">
            <div class="box-header">
                HEADER
            </div>

            <div class="box-body">
                @yield('content')
            </div>

            <div class="box-footer">
                FOOTER
            </div>
        </div>
    </div>
@endsection




