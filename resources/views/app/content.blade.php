@section('title', "$title_blog - $title_post")
@extends('layouts.app')
@section('content')
    <livewire:content-page :post_id="$post_id">
@endsection
