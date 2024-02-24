<div style="padding-top: 80px !important">
    <div
        class="container-fluid d-flex gap-0 no-padding flex-column justify-content-start align-items-center body-content">
        <div class="row d-flex gap-4 flex-column no-padding justify-content-center align-items-center"
            style="width: 100%;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="height: auto">
                <div class="row d-flex flex-column align-items-center justify-content-start">
                    <div class= "col-lg-10 col-md-10 col-sm-10 col-10 d-flex flex-row align-items-center justify-content-around"
                        style="margin-top: 100px; width:100%">
                        <a id="showPostByDate" class="text-center d-flex flex-column align-items-center justify-content-center"
                            href="{{ url(strtolower($current_menu) . '/' . strtolower($current_category) . '/' . $current_month . '/' . $current_year) }}">
                            <span class="calendar-icon"><i class="fa-solid fa-calendar-days"></i> &nbsp
                                {{ $post['created_at'] }}</span>
                        </a>
                        <div id="showCommentsPost" class="text-center d-flex flex-column align-items-center justify-content-center">
                            <span class="comment-icon-post"><i class="fa-solid fa-comment-dots"></i> &nbsp
                                {{ $post['total_comments'] }}
                                Komentar</span>
                        </div>
                        <a id="showPostByCategory" class="text-center d-flex flex-column align-items-center justify-content-center"
                            href="{{ url(strtolower($current_menu) . '/' . strtolower($current_category)) }}">
                            <span class="post-icon"><i class="fa-regular fa-file"></i> &nbsp {{ $post['category'] }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <hr style="width: 80%">
            <div class="col-lg-10 d-flex flex-column align-items-center justify-content-start mt-2">
                <h1 class="title-post text-center"><strong>{{ $post['title'] }}</strong></h1>
                <div class="{{$show_image_state ? "mt-5" : ""}}">
                    <img class="image-post" src="{{ asset('assets/images/' . $post['image']) }}" alt=""
                        style="display: {{ $show_image_state ? 'block' : 'none' }}">
                </div>
                <div class="mt-5 content ck-content" style="width: 100%">
                    {!! $post['content'] !!}
                </div>
            </div>
            <div class="col-lg-10 d-flex flex-row align-items-center justify-content-between my-5">
                @if ($previousPost)
                    <a class="nav-page" href="{{ url($previousPost['slug']) }}">
                        <i class="fa-solid fa-chevron-left icon-next-post"></i>
                        <div class="d-flex flex-column mx-3" style="width: auto">
                            <span>Sebelumnya</span>
                            <span>{{ $previousPost['title'] }}</span>
                        </div>
                    </a>
                @endif
                <hr class="vertical-hr"
                    style="@if (!$previousPost) margin-left: 50%; @elseif(!$nextPost) margin-right: 50%; @else margin: none; @endif">
                @if ($nextPost)
                    <a class="justify-content-end nav-page" href="{{ url($nextPost['slug']) }}">
                        <div class="d-flex flex-column mx-3" style="width: auto">
                            <span>Selanjutnya</span>
                            <span>{{ $nextPost['title'] ?? null }}</span>
                        </div>
                        <i class="fa-solid fa-chevron-right icon-next-post"></i>
                    </a>
                @endif
            </div>
            <div class="col-lg-10 d-flex flex-row gap-2 align-items-center justify-content-start mt-4">
                <i class="fa-solid fa-tags me-3"></i>
                @foreach ($post['tags'] as $tag)
                    <a class="post-tags" href="{{ url(strtolower($current_menu) . '/tag/' . strtolower($tag['name'])) }}"
                        style="text-decoration: none">
                        <div class="tag-button">
                            {{ $tag['name'] }}
                        </div>
                    </a>
                @endforeach
            </div>
            <div id="all-comments-post"
                class="col-lg-10 d-flex flex-column align-items-center justify-content-start mt-2">
                <hr style="width: 100%">
                <div class="d-flex mb-4 flex-row gap-4 justify-content-start align-items-center" style="width: 100%">
                    <i class="fa-solid fa-chevron-right arrow-comment"></i>
                    <span>Postingan ini memiliki {{ $post['total_comments'] }} komentar</span>
                </div>
                <div style="width: 100%">
                    {{-- {{dd(var_dump($comments_parents))}} --}}

                    @foreach ($comments as $comment)
                        <div class="d-flex flex-row justify-content-between align-items-center no-padding">
                            <div class="mx-1">
                                <img class="profile-image-comment"
                                    src="{{ asset('assets/user-profile-image/blank-profile.png') }}" alt="">
                            </div>
                            <div class="d-flex flex-column" style="width: 100%">
                                <div class="d-flex flex-row mx-1 justify-content-between align-items-center">
                                    <span class="username-comment ms-2">{{ $comment['username'] }}</span>
                                    <div class="d-flex flex-row gap-3 align-items-center ">
                                        <span>{{ $comment['created_at'] ?? null }}</span>
                                        <button
                                            wire:click="replyComment('{{ $comment['username'] }}', {{ $comment['id'] }})"
                                            type="button" class="btn-reply">
                                            <span style="color: var(--navbar-text-color)">Balas</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="ms-3" style="width: 95%">
                                    <span>{{ $comment['content'] }}</span>
                                </div>
                            </div>
                        </div>
                        <hr style="width: 100%;">
                        @foreach ($comments_parents as $comment_parent)
                            @if ($comment_parent['parent_id'] == $comment['id'])
                                <div class="d-flex flex-row no-padding justify-content-between align-items-center comment-child-1" 
                                style="margin-left: 50px;">
                                    <div class="mx-2">
                                        <img class="profile-image-comment"
                                            src="{{ asset('assets/user-profile-image/blank-profile.png') }}"
                                            alt="">
                                    </div>
                                    <div class=" d-flex flex-column"style="width: 100%">
                                        <div class="d-flex flex-row mx-1 justify-content-between align-items-center">
                                            <span class="username-comment ms-2">{{ $comment_parent['username'] }}</span>
                                            <div class="d-flex flex-row gap-3 align-items-center ">
                                                <span>{{ $comment_parent['created_at'] ?? null }}</span>
                                                <button
                                                    wire:click="replyComment('{{ $comment_parent['username'] }}', {{ $comment_parent['id'] }})"
                                                    type="button" class="btn-reply">
                                                    <span style="color: var(--navbar-text-color)">Balas</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="ms-3" style="width: 95%">
                                            <span>{{ $comment_parent['content'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <hr style="width: 100%;">
                                @foreach ($comments_parents as $comment_parent1)
                                    @if ($comment_parent1['parent_id'] == $comment_parent['id'])
                                        <div class="d-flex flex-row justify-content-between align-items-center comment-child-2"
                                            style="margin-left: 100px">
                                            <div class="mx-3">
                                                <img class="profile-image-comment"
                                                    src="{{ asset('assets/user-profile-image/blank-profile.png') }}"
                                                    alt="">
                                            </div>
                                            <div class="d-flex flex-column gap-2 "style="width: 100%">
                                                <div
                                                    class="d-flex flex-row mx-1 justify-content-between align-items-center">
                                                    <span
                                                        class="username-comment ms-2">{{ $comment_parent1['username'] }}</span>
                                                    <div class="d-flex flex-row gap-4 align-items-center ">
                                                        <span>{{ $comment_parent1['created_at'] ?? null }}</span>
                                                        {{-- <button
                                                            wire:click="replyComment('{{ $comment_parent1['username'] }}', {{ $comment_parent1['id'] }})"
                                                            type="button" class="btn-reply">
                                                            <span style="color: var(--navbar-text-color)">Balas</span>
                                                        </button> --}}
                                                    </div>
                                                </div>
                                                <div class="ms-3" style="width: 95%">
                                                    <span>{{ $comment_parent1['content'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="width: 100%;">
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                </div>
                <form wire:submit="createComment" action="">
                    <div class="mt-5 d-flex flex-column align-items-center" style="width: 100%">
                        <div class="d-flex flex-row justify-content-between align-items-center" style="width: 100%">
                            <span class="leave-comment mb-3"
                                style="display:{{ !$reply_comment_state ? 'block' : 'none' }}">Tinggalkan
                                Komentar</span>
                            <span class="leave-comment mb-3"
                                style="display:{{ $reply_comment_state ? 'block' : 'none' }}">Balas Komentar
                                <strong>{{ $selected_comment }}</strong></span>
                            <button wire:click.prevent="removeReplyComment()" class="cancel-btn-reply mb-3"
                                style="display:{{ $reply_comment_state ? 'block' : 'none' }}">Batal Balas</button>
                        </div>
                        <div style="width: 100%">
                            <div class="mb-3 row d-flex">
                                <div class="col-lg-12 mb-3">
                                    <textarea wire:model="comment_content" class="form-control" id="exampleFormControlTextarea1" rows="3"
                                        placeholder="Tulis komentar anda disini..."></textarea>
                                    @error('comment_content')
                                        <span class="error text-danger text-center">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <input wire:model="name" type="text" class="form-control"
                                        id="exampleFormControlInput1" placeholder="Nama (wajib)">
                                    @error('name')
                                        <span class="error text-danger text-center"
                                            style="width: auto">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <input wire:model="email" type="email" class="form-control"
                                        id="exampleFormControlInput1" placeholder="Email (wajib)">
                                    @error('email')
                                        <span class="error text-danger text-center"
                                            style="width: auto">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-switch my-3">
                            <input wire:model="save_identity_state" class="form-check-input" type="checkbox"
                                role="switch" id="flexSwitchCheckChecked">
                            <label class="form-check-label ms-2" for="flexSwitchCheckChecked">Simpan nama saya dan email
                                saya
                                di
                                browser ketika saya lain kali ingin berkomentar kembali</label>
                        </div>
                        <button type="submit" class="button-post-comment my-3">
                            <span wire:loading.remove wire:target="createComment"
                                style="color: var(--navbar-text-color)">Posting Komentar</span>
                            <div wire:loading wire:target="createComment" class="spinner-border spinner-border-sm"
                                role="status"></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
