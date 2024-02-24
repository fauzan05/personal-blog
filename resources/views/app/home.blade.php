@section('title', "$title_blog - $current_menu_name")
@extends('layouts.app')
@section('content')
    <livewire:main-page :current_menu_id="$current_menu_id" :current_menu_name="$current_menu_name">
@endsection
