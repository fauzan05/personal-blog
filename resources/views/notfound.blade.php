@section('title', 'Jelajah Buku - Tidak Ditemukan')
@extends('layouts.page_not_found')
@section('content')
    <div class="container-fluid d-flex flex-column justify-content-center align-items-center" style="height: 100dvh">
        <div class="row d-flex" style="width: 60%">
            <div class="col-lg-12 d-flex align-items-center justify-content-center flex-row shadow rounded" >
                    <img class="error-image " src="{{asset('assets/additional-image/404-not-found.jpg')}}" alt="">
                <div class="text-center " style="width: 100%">
                    <span><strong>Halaman/Konten yang anda cari tidak ditemukan</strong></span>
                    <a href="{{url('blog')}}">
                        Kembali ke Blog
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
