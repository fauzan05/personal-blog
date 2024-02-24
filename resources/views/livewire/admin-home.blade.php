<div class="container-fluid dashboard-content position-relative z-2 ">
    <div class="d-flex flex-row justify-content-between align-items-center" style="width: 100%">
        @if (session('status'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <span class="admin-welcome mb-3">Selamat Datang Admin!</span>
    </div>
    <div class="row d-flex flex-column no-padding align-items-center justify-content-center">
        <div class="col-lg-12 no-padding border card">
            <div class="row no-padding d-flex justify-content-center align-items-center" style="height: auto">
                <div class="col-lg-2 m-3 mini-card shadow-sm rounded-3 border" style="height: 100px; width: auto;">
                    <i class="icon fa-solid fa-file-signature mx-2"></i>
                    <div class="d-flex flex-column align-items-center justify-content-center flex-column mx-2">
                        <span>{{ $posts }}</span>
                        <span>Postingan</span>
                    </div>
                </div>
                <div class="col-lg-2 m-3 mini-card shadow-sm rounded-3 border" style="height: 100px; width: auto;">
                    <i class="icon fa-solid fa-list mx-2"></i>
                    <div class="d-flex flex-column align-items-center justify-content-center flex-column mx-2">
                        <span> {{ $categories }}</span>
                        <span>Kategori</span>
                    </div>
                </div>
                <div class="col-lg-2 m-3 mini-card shadow-sm rounded-3 border" style="height: 100px; width: auto;">
                    <i class="icon fa-solid fa-tags mx-2"></i>
                    <div class="d-flex flex-column align-items-center justify-content-center flex-column mx-2">
                        <span>{{ $tags }}</span>
                        <span>Tag</span>
                    </div>
                </div>
                <div class="col-lg-2 m-3 mini-card shadow-sm rounded-3 border" style="height: 100px; width: auto;">
                    <i class="icon fa-solid fa-comments mx-2"></i>
                    <div class="d-flex flex-column align-items-center justify-content-center flex-column mx-2">
                        <span>{{ $comments }}</span>
                        <span>Komentar</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 border card mt-5">
            <span class="text-center my-3"><strong>Daftar Jejak Catatan Para Pembaca</strong></span>
            <div class="row d-flex ms-1 flex-row justify-content-around align-items-start"
                style="max-height: 50dvh; overflow-y:auto;">
                @foreach ($notes as $note)
                    <div class="col-lg-4 col-md-6 card-note-admin">
                        <div class="m-3 border rounded">
                            <div class="row d-flex flex-column justify-content-center align-items-center">
                                <div class="col-lg-12 d-flex flex-row justify-content-around align-items-center">
                                    <span><strong>{{ $note['title'] }}</strong></span>
                                    <div wire:click="deleteNote({{$note['id']}}, '{{$note['title']}}')" class="trash-icon m-3">
                                        <i class="fa-solid fa-trash"></i>
                                    </div>
                                </div>
                                <hr style="width: 80%">
                                <div class="col-lg-12 text-center note-wrapper-admin">
                                    <span>{{ $note['content'] }}</span>
                                </div>
                                <hr style="width: 80%">
                                <div class="col-lg-12 mb-2 text-center">
                                    <span>~ {{ $note['author'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
