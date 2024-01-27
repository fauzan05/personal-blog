<div class="container-fluid dashboard-content position-relative z-2 ">
    <div class="d-flex flex-row justify-content-between align-items-center" style="width: 100%">
        @if(session('status'))
        <div class="alert alert-success mt-3" role="alert">
            {{session('status')}}
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
        
    </div>
</div>
