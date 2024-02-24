
@section('title', "Jelajah Buku - Pencarian untuk \"" . $current_search_keyword . "\"")
@extends('layouts.app')
@section('content')
    <livewire:search-page
     :current_search_keyword="$current_search_keyword">
@endsection
