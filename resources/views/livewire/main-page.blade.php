<div style="padding-top: 80px !important">
    <div
        class="container-fluid d-flex gap-0 no-padding flex-column justify-content-start align-items-center body-content">
        <div class="row d-flex gap-4 flex-column no-padding justify-content-center align-items-center"
            style="width: 100%;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-8 no-padding mb-5 position-relative" style="height: 100%">
                {{-- <img class="background-image" src="" alt=""> --}}
                <div class="row no-padding d-flex flex-column justify-content-center align-items-center"
                    style="width: 100%;">
                    <div class="col-md-12 col-sm-12 col-lg-10 d-flex flex-column align-items-center">
                        <span class="title-menu mt-5"> <strong>{{ ucwords($current_menu_name) }}</strong></span>
                    </div>
                    <div class="col-lg-10 col-md-12 col-sm-12 mt-4 my-12">
                        <div class="row d-flex flex-row align-items-center justify-content-center">
                            @if ($categories)
                                @foreach ($categories as $key => $category)
                                    <a class="col-lg-3 col-md-3 col-sm-3 m-2 d-flex card-category rounded flex-row justify-content-center align-items-center"
                                        href="{{ url(strtolower($current_menu_name) . '/' . strtolower($category)) }}"
                                        style="text-decoration: none; height: 50px;">
                                        <span class="title-category ">{{ $category }}</span>
                                    </a>
                                @endforeach
                            @else
                                <div id="inputHelp" class="form-text text-center" style="font-size: 1.2rem">Kategori
                                    Belum Tersedia</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 my-5 d-flex flex-column align-items-center">
                        <div id="all-posts" class="border rounded mt-5 all-posts-button">
                            <span class="all-post-title mx-3">Semua Postingan</span>
                        </div>
                        <hr style="width: 90%; color: var(--text-color)">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-12 my-5 d-flex flex-column align-items-center justify-content-start no-padding">
                <div class="row d-flex flex-row justify-content-center align-items-start" style="width: 100%; ">
                    @if ($posts)
                        @foreach ($posts as $key => $post)
                            <a wire:key="{{ $key }}" class="col-lg-3 col-md-5 col-sm-10 col-10 mx-2 my-5 shadow-sm card-post"
                                href="{{ url($post['slug']) }}" style="text-decoration: none">
                                <div class="d-flex my-3 flex-column align-items-center">
                                    <img class="rounded mt-2 image-card-post"
                                        src="{{ asset('assets/images/' . $post['image']) }}" alt="">
                                    <div class="wrapper-post-title mt-3">
                                        <span class="card-post-title text-center">
                                            {{ $post['title'] }}
                                        </span>
                                        <hr class="my-3" style="width: 80%">
                                    </div>
                                </div>
                                <div class="card-post-footer my-3">
                                    <div id="inputHelp" class="form-text" style="font-size: 0.7rem">
                                        {{ $post['created_at'] }}</div>
                                    &nbsp
                                    <span cl-ass="dot">
                                        •
                                    </span>
                                    &nbsp
                                    <div id="inputHelp" class="form-text" style="font-size: 0.7rem">
                                        {{ (int) $post['total_comments'] }} Komentar
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div id="inputHelp" class="form-text text-center" style="font-size: 1.2rem">Postingan Belum
                            Tersedia</div>
                    @endif
                </div>
            </div>
            <div class="col-lg-12 mt-5 d-flex flex-column align-items-center justify-content-center">
                <nav aria-label="Page navigation example" style="display: {{ $posts ? 'block' : 'none' }}">
                    @php
                        $i = 1;
                        $range = 3;
                    @endphp
                    <ul class="pagination">
                        @for ($i = 1; $i <= $last_page; $i++)
                            @if ($i == 1 || $i == $last_page || ($i >= $selected_page - $range && $i <= $selected_page + $range))
                                <li class="page-item">
                                    <a wire:click.prevent="getPage({{ $i }})" class="page-link"
                                        style="cursor: pointer; background-color: {{ $selected_page == $i ? 'var(--navbar-color)' : 'var(--main-color)' }};">
                                        <span wire:loading.remove wire:target="getPage({{ $i }})"
                                            style="color: {{ $selected_page == $i ? 'var(--navbar-text-color)' : 'var(--text-color)' }}">{{ $i }}</span>
                                        <div wire:loading wire:target="getPage({{ $i }})"
                                            class="spinner-border spinner-border-sm" role="status" style="color: {{ $selected_page == $i ? 'var(--navbar-text-color)' : 'var(--navbar-color)' }}"></div>
                                    </a>
                                </li>
                            @elseif (($i == $selected_page - $range - 1 && $i != 1) || ($i == $selected_page + $range + 1 && $i != $last_page))
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endfor
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
