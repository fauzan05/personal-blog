<div style="padding-top: 80px">
    <div class="container d-flex flex-column justify-content-center align-items-center body-content" style="height: auto; width: 100%">
        <div class="row d-flex flex-column align-items-center justify-content-center no-padding" style="width: 100%;">
            <div class="col-lg-12">
                <h1 class="title-about"><span><strong>About</strong></span></h1>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 d-flex flex-column align-items-center justify-content-between mb-5" >
                <div class="row about-wrapper">
                    <div class="col-lg-7 col-md-7 col-sm-12 col-12 d-flex flex-column align-items-center justify-content-center">
                        <p class="about-content">
                            Jelajah buku lahir dari kecintaan penulis membaca buku. Setelah membaca buku ternyata ada yang menganjal. Kenapa tidak dibagikan pengalaman membaca ini? Akhirnya lahirlah Jelajah Buku di muka bumi ini. Lewat jelajah buku ini, penulis juga bisa memotivasi diri. Untuk selalu meluangkan waktu untuk buku, menjelajah ke luar rumah dan menulisakan kembali untuk bahan pengingat diri. Mungkin lewat tulisan sederhana di sini mampu menularkan efek kecintaan membaca buku. Bahwa membaca buku itu menyenangkan. Apalagi bersama orang tersayang. Setiap orang akan menemukan bukunya sendiri-sendiri. Maka mulai jatuh cintalah dengan buku. Kelak kalian akan menemukan jodohmu. Lagi- lagi membaca buku bukan sekadar menjelajah buku saja. Tetapi kita belajar mejelajah ruang, waktu dan alur cerita dalam hidup yang terus berjalan. Mari menjelajah buku.                        </p>
                    </div>
                    <div class="col-lg-5 col-md-4 col-sm-4 col-12">
                        <img class="logo-about" src="{{ asset('assets/logo/' . $logo_filename) }}" alt="" style="width: 100%">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-5">
                <img class="image-about" src="{{ asset('assets/user-profile-image/' . $profile_image) }}"
                    alt="">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 my-5">
                <h2 class="text-center"><span class="notes-title">Jejak Catatan Para Pembaca</span></h2>
                <hr class="mb-5">
                <div class="d-flex flex-row justify-content-center align-items-center">
                    @if (empty($notes))
                        <div id="inputHelp" class="form-text text-center">Catatan kosong/belum dibuat</div>
                    @else
                        <button id="left-button" class="nav-note-button mx-2"><i
                                class="fa-solid fa-chevron-left"></i></button>
                        <div class="card-note-wrapper" style="grid-template-columns: repeat({{count($notes)}}, 1fr)">
                            @foreach ($notes as $key => $note)
                                <div class="card-note">
                                    <span class="title m-2">{{ $note['title'] }}</span>
                                    <span class="m-2 content"> " &nbsp{{ $note['content'] }}&nbsp"</span>
                                    <span class="m-2 text-end">~{{ $note['author'] }}</span>
                                    <span class="ms-3"> {{ $key + 1 }} dari {{ count($notes) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <button id="right-button" class="nav-note-button mx-2"><i
                                class="fa-solid fa-chevron-right"></i></button>
                    @endif
                </div>
            </div>
            <div class="col-lg-12 mt-3 d-flex flex-column align-items-center">
                <span class="text-center">Silahkan tinggalkan jejak kamu di bawah ini</span>
                <span> <i class="fa-regular fa-hand-pointer mt-2"></i></span>
            </div>
            <div class="col-lg-12 mt-5 d-flex flex-column justify-content-center align-items-center">
                <form action="" wire:submit="createNote"
                    class="d-flex flex-column justify-content-start align-items-center" style="width: 50%">
                    <div class="mb-3" style="width: 100%">
                        <label for="exampleFormControlInput1" class="form-label">Judul</label>
                        <input wire:model="title" type="text" class="form-control" id="exampleFormControlInput1"
                            placeholder="Masukkan judul...">
                        @error('title')
                            <span class="error mt-1 text-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3" style="width: 100%">
                        <label for="exampleFormControlTextarea1" class="form-label">Isi</label>
                        <textarea wire:model="content" class="form-control" id="exampleFormControlTextarea1" rows="3"
                            placeholder="Masukkan konten/isi..."></textarea>
                        @error('content')
                            <span class="error mt-1 text-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3" style="width: 100%">
                        <label for="exampleFormControlInput2" class="form-label">Penulis</label>
                        <input wire:model="author" type="text" class="form-control" id="exampleFormControlInput2"
                            placeholder="Masukkan nama anda...">
                        @error('author')
                            <span class="error mt-1 text-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="create-note-button">
                        <span wire:loading.remove wire:target="createNote"
                            style="color: var(--navbar-text-color)">Buat</span>
                        <div wire:loading wire:target="createNote" class="spinner-border spinner-border-sm"
                            role="status" style="color: var(--navbar-color)"></div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
