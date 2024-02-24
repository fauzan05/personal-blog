@section('title', "Jelajah Buku - $current_category_name")
@extends('layouts.app')
@section('content')
    <livewire:second-page
     :current_menu_id="$current_menu_id" :current_category_name="$current_category_name"
     :current_category_id="$current_category_id" :selected_month="$selected_month" :selected_year="$selected_year">
@endsection
