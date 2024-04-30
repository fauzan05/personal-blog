@php
$app = App\Models\ApplicationSettings::select('blog_name')->first()->blog_name ?? null
@endphp
@section('title', "$app- Personal Blog")
@extends('layouts.app')
@section('content')
    <div style="padding-top: 80px">
        <div class="container-fluid d-flex flex-column justify-content-start align-items-center body-content" style="height: auto">
            <div class="row d-flex flex-column justify-content-start align-items-center">
                <div class="container-card-index p-0 row" style="width: 50%;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 wrapper-profile-image">
                        <img class="image-profile-index m-5" src="{{ asset('assets/user-profile-image/' . $image_profile) }}"
                            alt="">
                        <div class="button-title-index">
                            <span
                                class="title-index"><strong>{{ $app }}</strong>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 d-flex mt-2 flex-column align-items-center wrapper-content-index"
                        style="line-height: 1.5cm; width: 100%;">
                        <p class="content-index m-4 pt-3">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </p>
                        <a class="button-blog-index" href="{{url('blog')}}" 
                        >Masuk Ke Blog</a> 
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection