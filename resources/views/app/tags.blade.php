@section('title', "Jelajah Buku - $current_tag_name")
@extends('layouts.app')
@section('content')
    <livewire:third-page
     :current_menu_id="$current_menu_id" :current_tag_name="$current_tag_name"
     :current_tag_id="$current_tag_id">
@endsection
