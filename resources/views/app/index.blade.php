@section('title', 'Jelajah Buku - Personal Blog')
@extends('layouts.app')
@section('content')
    <div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="height: 100dvh">
        {{$path ?? ''}}
    </div>
@endsection