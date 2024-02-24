<div class="container-fluid dashboard-content position-relative z-2 ">
    <div class="d-flex flex-row justify-content-between align-items-center mb-4" style="width: 100%">
        @if (session('status'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <span class="admin-welcome mb-3">Menu Postingan &nbsp <i class="fa-solid fa-file"></i></span>
        @if (session('post_status'))
            <div class="alert alert-success text-center" role="alert" style="width: 20%;">
                {{ session('post_status')['message'] }}
            </div>
        @endif
        <div class="d-flex flex-row gap-3">
            <button wire:click="configMode()" class="btn btn-primary">
                {{ $configState ? 'Buat Postingan' : 'Konfigurasi Postingan' }}
                &nbsp
                <div wire:loading wire:target="configMode()" class="spinner-border spinner-border-sm" role="status">
                </div>
            </button>
        </div>
    </div>
    <div class="row d-flex flex-row no-padding align-items-start justify-content-center">
        <div class="col-lg-12 d-flex card flex-column justify-content-center align-items-center">
            <span class="create-post my-3">{{ $configState ? 'Edit Postingan' : 'Buat Postingan' }}</span>
            <hr class="no-padding" style="width: 80%">
            @if (session('post_title_conflict'))
                <div class="alert alert-danger mt-3" role="alert">
                    {{ session('post_title_conflict')['message'] }}
                </div>
            @endif
            <div style="width: 80% !important;">
                <form class="my-4" wire:submit="{{ $configState ? 'editPost' : 'post' }}" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" wire:model="{{ $configState ? 'update_title' : 'title' }}"
                            class="form-control" id="title" placeholder="Masukkan Judul">
                        <div id="inputHelp" class="form-text">Panjang max. 50 karakter</div>
                        @error('title')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                        @error('update_title')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Gambar Utama</label>
                        <input class="form-control" wire:model="{{ $configState ? 'update_image' : 'image' }}"
                            type="file" id="formFile1">
                        <div id="inputHelp" class="form-text">Ukuran min. 20Kb, Max. 50Mb. Format gambar.</div>
                        @if ($configState)
                            <div id="inputHelp" class="form-text">Gambar terkini : {{ $update_image ?? ' -' }}
                            </div>
                        @endif
                        @error('image')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input wire:model="show_image_state" class="form-check-input" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked">
                        <label class="form-check-label" for="flexSwitchCheckChecked">Tampilkan Gambar Utama Di
                            Postingan</label>
                    </div>
                    @if ($configState)
                        <div class="d-flex flex-column my-3">
                            <span>Pratinjau</span>
                            <img class="my-3 rounded"
                                src="{{ asset('assets/images/' . ((bool) $selected_post_state ? $current_image : '')) }}"
                                alt="Gambar Pratinjau" style="width: 150px;">
                        </div>
                    @endif

                    <div wire:ignore style="width: 100% !important;">
                        <label for="body-update" class="form-label">Bodi</label>
                        <div>
                            <textarea class="form-control" id="body" wire:model.defer="{{ $configState ? 'body_update' : 'body' }}"
                                style="width: 100% !important;"></textarea>
                        </div>
                    </div>
                    {{-- <div wire:ignore style="width: 100% !important;">
                        <label for="body-update" class="form-label">Bodi</label>
                        
                        <div>
                            <textarea class="form-control" id="body" wire:model.defer="{{ $configState ? 'body_update' : 'body' }}"
                                style="width: 100% !important;"></textarea>
                        </div>
                    </div>
                    --}}
                    @error('body')
                        <span class="error text-danger">{{ $message }}</span>
                    @enderror 
                    @if ($configState)
                        <div class="d-flex flex-column my-3">
                            <span class="created-at">Dibuat : {{ !empty($created_at) ? $created_at : ' -' }}</span>
                            <span class="updated-at">Diperbarui :
                                {{ !empty($updated_at) ? $updated_at : ' -' }}</span>
                        </div>
                    @endif
                    <button type="submit" wire:click="loadingCreatePostState()" class="btn btn-primary my-4"
                        style="width: 100%">
                        <div wire:loading wire:target="loadingCreatePostState()"
                            class="spinner-border spinner-border-sm" role="status">
                        </div>
                        {{ $configState ? 'Edit Postingan' : 'Buat Postingan' }}
                    </button>
                    @if ($configState)
                        <button type="button" wire:click="deletePost()" class="btn btn-danger my-1"
                            style="width: 100%">
                            <div wire:loading wire:target="deletePost()" class="spinner-border spinner-border-sm"
                                role="status">
                            </div>
                            Hapus Postingan
                        </button>
                    @endif
            </div>
        </div>
        <div class="col-lg-12 mt-5" style="width: 100%; height: auto;">
            <div class="row d-flex flex-row">
                @if ($configState)
                    <div
                        class="col-lg-12 container card d-flex flex-column justify-content-center align-items-center mb-5">
                        <span class="create-post text-center my-3">Daftar Postingan</span>
                        <div class="my-2 d-flex gap-2 flex-row" style="width: 80%">
                            <input class="form-control form-control-sm" type="text" wire:model.live="searchTitle"
                                wire:keydown.enter="searchPost()" wire:keydown.escape="setDefaultSearchPost()"
                                placeholder="Cari judul postingan..." aria-label=".form-control-sm example">
                            <button type="button" wire:click="searchPost()"
                                class="btn btn-primary btn-sm">Cari</button>
                        </div>
                        <hr class="no-padding" style="width: 80%">
                        <div class="list-posts-collection d-flex flex-column justify-content-start align-items-center">
                            @if (!empty($posts))
                                @foreach ($posts as $post)
                                    <div wire:click="selectedPost({{ $post['id'] }})"
                                        class="list-group my-3 gap-2 list-post" style="width: 80%">
                                        <div class="list-group-item shadow-sm list-group-item-action {{ $selected_post_id === $post['id'] && $selected_post_state ? 'active' : '' }}"
                                            aria-current="true" style="transition: var(--tran-04)">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $post['title'] }}</h5>
                                            </div>
                                            <hr>
                                            <small>{{ $post['created_at'] }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif(empty($posts))
                                <div
                                    class="list-posts-collection d-flex flex-column justify-content-center align-items-center">
                                    <div id="inputHelp" class="form-text text-center">Postingan kosong/tidak
                                        ditemukan</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                {{-- Tag --}}
                <div class="col-lg-12 mb-5 d-flex flex-column justify-content-center align-items-center card"
                    style="width: 100%;">
                    <span class="choose-tags my-2">Pilih Tag</span>
                    <hr class="no-padding" style="width: 80%">
                    @if (session('tag_status'))
                        <div class="d-flex flex-column mt-3 text-center justify-content-center align-items-center"
                            style="width: 80%">
                            <div class="alert alert-{{ session('tag_status')['color'] }} gap-3 d-flex flex-column"
                                role="alert">
                                {{ session('tag_status')['message'] }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    <div class="tags-collection m-3 d-flex flex-column justify-content-start align-items-start">
                        @if (empty($tags))
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div id="inputHelp" class="form-text">Tags kosong/belum dibuat</div>
                                @if ($configState)
                                    @error('update_current_tag')
                                        <span class="error text-danger text-center">{{ $message }}</span>
                                    @enderror
                                @endif
                                @error('selected_tag')
                                    <span class="error text-danger text-center">{{ $message }}</span>
                                @enderror
                                @error('update_current_tag')
                                    <span class="error text-danger text-center">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif(!empty($tags))
                            {{-- Jika tag tidak kosong --}}
                            @foreach ($tags as $key => $tag)
                                <div class="d-flex flex-row justify-content-between gap-3 align-items-center my-1"
                                    style="width: 100%">
                                    <div class="form-check">
                                        @if (!$optionTagState)
                                            @if (!$selected_post_state)
                                                {{-- Jika tidak ada pembungkus, maka error karena selected_tag properti yang bersifat array --}}
                                                <div>
                                                    <input class="form-check-input"
                                                        wire:model="selected_tag.{{ $tag['id'] }}" type="checkbox"
                                                        id="tag{{ $tag['id'] }}"
                                                        style="transition: var(--tran-05)">
                                                    <label class="form-check-label" for="tag{{ $tag['id'] }}">
                                                        {{ $tag['name'] }}
                                                </div>
                                            @elseif($selected_post_state)
                                                <div>
                                                    @php
                                                        $tagPrinted = false;
                                                    @endphp
                                                    {{-- {{dd($update_current_tag[$tag['id']]['name'])}} --}}
                                                    @if (isset($update_current_tag[$tag['id']]) &&
                                                            is_array($update_current_tag[$tag['id']]) &&
                                                            $tag['id'] == $update_current_tag[$tag['id']]['id']
                                                    )
                                                        <div class="d-flex flex-row justify-content-between gap-3 align-items-center my-1"
                                                            style="width: 100%">
                                                            <div class="form-check">
                                                                <input class="form-check-input"
                                                                    wire:model="update_current_tag.{{ $tag['id'] }}"
                                                                    type="checkbox"
                                                                    id="flexCheckDefault{{ $tag['id'] }}"
                                                                    style="transition: var(--tran-05)" checked>
                                                                <label class="form-check-label"
                                                                    for="flexCheckDefault{{ $tag['id'] }}">
                                                                    {{ $update_current_tag[$tag['id']]['name'] }}
                                                            </div>
                                                        </div>
                                                        @php
                                                            $tagPrinted = true;
                                                        @endphp
                                                    @endif
                                                    @if (!$tagPrinted)
                                                        <div>
                                                            <input class="form-check-input"
                                                                wire:model="update_current_tag.{{ $tag['id'] }}"
                                                                type="checkbox" id="tag{{ $tag['id'] }}"
                                                                style="transition: var(--tran-05)">
                                                            <label class="form-check-label"
                                                                for="tag{{ $tag['id'] }}">
                                                                {{ $tag['name'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @elseif($optionTagState)
                                            <div class="d-flex flex-row option justify-content-between">
                                                <input wire:model="update_tag.{{ $tag['id'] }}"
                                                    class="form-control form-control-sm" type="text"
                                                    placeholder="{{ $tag['name'] }}"
                                                    aria-label=".form-control-sm example">
                                                <div
                                                    class="d-flex flex-row ms-2 gap-2 justify-content-center align-items-center">
                                                    {{-- {{var_dump($tag['post'])}} --}}
                                                    @if (!$tag['has_post'])
                                                        <button type="button"
                                                            wire:click="deleteTag('{{ $tag['id'] }}')"
                                                            class="btn btn-danger delete-tag btn-sm d-flex flex-row">
                                                            <i class="d-flex fa-solid fa-delete-left"></i>
                                                            &nbsp
                                                            <div wire:loading
                                                                wire:target="deleteTag('{{ $tag['id'] }}')"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status">
                                                            </div>
                                                        </button>
                                                    @endif
                                                    <button id="save-tag" type="button"
                                                        wire:click="editTag({{ $key }})"
                                                        class="btn btn-success save-tag btn-sm d-flex flex-row">
                                                        <i class="fa-solid fa-check"></i>
                                                        &nbsp
                                                        <div wire:loading wire:target="editTag('{{ $key }}')"
                                                            class="spinner-border spinner-border-sm" role="status">
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @error('selected_tag')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                            @error('update_current_tag')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                    <div class="d-flex flex-row gap-3 my-3">
                        <button type="button" wire:click="setCreateTagState()" class="btn btn-outline-dark">
                            <div wire:loading wire:target="setCreateTagState()"
                                class="spinner-border spinner-border-sm" role="status">
                            </div>
                            @if (!$createTagState)
                                Buat Tag &nbsp <i class='fa-solid fa-pen'></i>
                            @elseif($createTagState)
                                Tutup
                            @endif
                        </button>
                        <button id="option-tag-button" type="button" wire:click="optionTag()"
                            class="btn btn-outline-danger">
                            <div wire:loading wire:target="optionTag()" class="spinner-border spinner-border-sm"
                                role="status" id="liveAlertBtn">
                            </div>
                            @if (!$optionTagState)
                                Edit Tag &nbsp<i class="fa-solid fa-pen-to-square"></i>
                            @elseif($optionTagState)
                                Tutup
                            @endif
                        </button>
                    </div>
                    <div id="inputHelp" class="form-text my-3 text-center" style="width: 80%">Catatan: <br> Tag yang
                        sudah
                        dipakai di post lain tidak bisa dihapus. Nama Tag tidak boleh sama.</div>
                    @if ($createTagState)
                        </form>
                    @endif
                    @if ($createTagState)
                        <div class="d-flex flex-column align-items-center justify-content-center my-4"
                            style="width: 80%">
                            <form wire:submit="createTag">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fa-solid fa-tag"></i></span>
                                        <input type="text" wire:model="tag" class="form-control"
                                            placeholder="Tag" aria-describedby="basic-addon1">
                                        @error('tag')
                                            <span class="error text-danger text-center my-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" wire:click="loadingCreateTagState()"
                                        class="btn btn-outline-primary">
                                        <div wire:loading wire:target="loadingCreateTagState()"
                                            class="spinner-border spinner-border-sm" role="status">
                                        </div>
                                        Buat Tag
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                {{-- CATEGORIES --}}
                <div class="col-lg-12 d-flex mb-5 flex-column justify-content-center align-items-center card"
                    style="width: 100%">
                    <span class="choose-categories my-2">Pilih Kategori</span>
                    <hr class="no-padding" style="width: 80%">
                    @if (session('category_status'))
                        <div class="d-flex flex-column mt-3 text-center justify-content-center align-items-center"
                            style="width: 80%">
                            <div class="alert alert-{{ session('category_status')['color'] }} gap-3 d-flex flex-column"
                                role="alert">
                                {{ session('category_status')['message'] }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    <div class="categories-collection m-3 d-flex flex-column justify-content-start align-items-start">
                        @if (empty($categories))
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div id="inputHelp" class="form-text">Kategori kosong/belum dibuat</div>
                                @error('selected_category')
                                    <span class="error text-danger text-center">{{ $message }}</span>
                                @enderror
                                @error('update_current_category')
                                    <span class="error text-danger text-center">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif(!empty($categories))
                            @foreach ($categories as $key => $category)
                                <div class="d-flex flex-row justify-content-between gap-3 align-items-center my-1"
                                    style="width: 100%">
                                    <div class="form-check">
                                        {{-- cek apakah opsi optionCategoryState true/false --}}
                                        @if (!$optionCategoryState)
                                            @if (!$selected_post_state)
                                                <input class="form-check-input" wire:model="selected_category"
                                                    type="radio" name="exampleRadios"
                                                    id="category{{ $key + 1 }}" value="{{ $category['id'] }}">
                                                <label class="form-check-label" for="category{{ $key + 1 }}">
                                                    {{ $category['name'] }}
                                                </label>
                                            @elseif($selected_post_state)
                                                <input class="form-check-input" wire:model="update_current_category"
                                                    type="radio" name="exampleRadios"
                                                    id="category{{ $key + 1 }}" value="{{ $category['id'] }}">
                                                <label class="form-check-label" for="category{{ $key + 1 }}">
                                                    {{ $category['name'] }}
                                                </label>
                                            @endif
                                            {{-- Edit kategori --}}
                                        @elseif($optionCategoryState)
                                            <div class="d-flex flex-row option justify-content-between">
                                                <input wire:model="update_category.{{ $category['id'] }}"
                                                    class="form-control form-control-sm" type="text"
                                                    placeholder="{{ $category['name'] }}"
                                                    aria-label=".form-control-sm example">
                                                <div
                                                    class="d-flex flex-row ms-2 justify-content-center gap-2 align-items-center">
                                                    {{-- {{var_dump($tag['post'])}} --}}
                                                    @if (!$category['has_post'])
                                                        <button type="button"
                                                            wire:click="deleteCategory('{{ $category['id'] }}')"
                                                            class="btn btn-danger delete-tag btn-sm d-flex flex-row">
                                                            <i class="d-flex fa-solid fa-delete-left"></i>
                                                            &nbsp
                                                            <div wire:loading
                                                                wire:target="deleteCategory('{{ $category['id'] }}')"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status">
                                                            </div>
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        wire:click="editCategory('{{ $category['name'] }}')"
                                                        class="btn btn-success save-tag btn-sm d-flex flex-row">
                                                        <i class="fa-solid fa-check"></i>
                                                        &nbsp
                                                        <div wire:loading
                                                            wire:target="editCategory('{{ $category['name'] }}')"
                                                            class="spinner-border spinner-border-sm" role="status">
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @error('selected_category')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                            @error('update_current_category')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                    @if ($createCategoryState)
                        </form>
                    @endif
                    <div class="d-flex flex-row gap-3 my-3">
                        <button type="button" wire:click="setCreateCategoryState()"
                            class="btn my-3 btn-outline-dark">
                            <div wire:loading wire:target="setCreateCategoryState()"
                                class="spinner-border spinner-border-sm" role="status">
                            </div>
                            @if (!$createCategoryState)
                                Buat Kategori &nbsp <i class='fa-solid fa-pen'></i>
                            @elseif($createCategoryState)
                                Tutup
                            @endif
                        </button>
                        <button type="button" wire:click="optionCategory()" class="btn my-3 btn-outline-danger">
                            <div wire:loading wire:target="optionCategory()" class="spinner-border spinner-border-sm"
                                role="status" id="liveAlertBtn">
                            </div>
                            @if (!$optionCategoryState)
                                Edit Kategori &nbsp<i class="fa-solid fa-pen-to-square"></i>
                            @elseif($optionCategoryState)
                                Tutup
                            @endif
                        </button>
                    </div>
                    <div id="inputHelp" class="form-text my-3 text-center" style="width: 80%">Catatan: <br> Kategori
                        yang
                        sudah
                        dipakai di post lain tidak bisa dihapus. Nama Kategori tidak boleh sama.</div>
                    @if (session('create_category_status'))
                        <div class="alert alert-success mt-3 text-center" role="alert" style="width: 80%">
                            {{ session('create_category_status') }}
                        </div>
                    @endif
                    @if ($createCategoryState)
                        <div class="d-flex flex-column align-items-center justify-content-center my-4"
                            style="width: 80%">
                            <form wire:submit="createCategory">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text" id="basic-addon1"><i
                                                class="fa-solid fa-list"></i></span>
                                        <input type="text" wire:model="category" class="form-control"
                                            placeholder="Kategori" aria-describedby="basic-addon1">
                                        @error('category')
                                            <span class="error text-danger text-center my-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <button type="submit" wire:click="loadingCreateCategoryState()"
                                        class="btn btn-outline-primary">
                                        <div wire:loading wire:target="loadingCreateCategoryState()"
                                            class="spinner-border spinner-border-sm" role="status">
                                        </div>
                                        Buat Kategori
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="col-lg-12 d-flex mb-5 flex-column justify-content-center align-items-center card">
                    <span class="choose-address my-2">Pilih Menu</span>
                    <hr class="no-padding" style="width: 80%">
                    <div class="form-group"> <!-- Tambahkan kelas form-group di sini -->
                        @foreach ($menus as $key => $menu)
                            <div class="d-flex flex-column justify-content-center align-items-start form-check-inline">
                                <!-- Tambahkan kelas form-check-inline di sini -->
                                @if (!$selected_post_state)
                                    <div class="form-check my-3">
                                        <input class="form-check-input" wire:model="selected_menu" type="radio"
                                            name="menu" id="menu{{ $key }}"
                                            value="{{ $menu['id'] }}">
                                        <label class="form-check-label" for="menu{{ $key }}">
                                            {{ $menu['name'] }}
                                        </label>
                                    </div>
                                @elseif($selected_post_state)
                                    <div class="form-check my-3">
                                        <input class="form-check-input" wire:model="update_current_menu"
                                            type="radio" name="menu" id="menu{{ $key }}"
                                            value="{{ $menu['id'] }}">
                                        <label class="form-check-label" for="menu{{ $key }}">
                                            {{ $menu['name'] }}
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @error('selected_menu')
                        <span class="error text-danger text-center">{{ $message }}</span>
                    @enderror
                </div>
                {{-- <div class="col-lg-12 d-flex mb-5 flex-column justify-content-center align-items-center card">
                    <span class="choose-address my-2">Pilih Lokasi</span>
                    <hr class="no-padding" style="width: 80%">
                    @if ($selected_post_state)
                        <div id="inputHelp" class="form-text d-flex flex-colum align-items-center"
                            style="width: 80%">Lokasi Sekarang : <br>
                            {{ $update_custom_address }}
                        </div>
                    @endif
                    <div class="address-collection m-3 d-flex flex-column justify-content-start align-items-start">
                        @if (empty($addresses))
                            <div id="inputHelp" class="form-text text-center">Alamat kosong/belum dibuat. Buat
                                lokasi
                                di pengaturan atau kustom yang instan.</div>
                            @error('selected_address')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                            @error('update_selected_address')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                        @elseif(!empty($addresses))
                            @foreach ($addresses as $key => $address)
                                <div class="form-check my-1">
                                    <input class="form-check-input"
                                        wire:model="{{ !$selected_post_state ? 'selected_address' : 'update_selected_address' }}"
                                        type="radio" name="addressRadios" id="address{{ $key + 1 }}"
                                        value="{{ $address['id'] }}">
                                    <label class="form-check-label" for="address{{ $key + 1 }}">
                                        {{ $address['province'] }}, {{ $address['country'] }}
                                    </label>
                                </div>
                            @endforeach
                            @error('selected_address')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                    @if ($createLocationState)
                        </form>
                    @endif
                    <button type="button" wire:click="createAddress()" class="btn my-3 btn-outline-dark">
                        <div wire:loading wire:target="createAddress()" class="spinner-border spinner-border-sm"
                            role="status">
                        </div>
                        @if (!$createLocationState)
                            Buat Lokasi Kustom &nbsp <i class='fa-solid fa-pen'></i>
                        @elseif($createLocationState)
                            Tutup
                        @endif
                    </button>
                    @if ($createLocationState)
                        {{-- <livewire:mini-create-address> --}}
                {{-- <div class="d-flex flex-column align-items-center justify-content-center my-4">
                            <div class="mb-3" style="width: 80%">
                                <label for="input1" class="form-label">Masukkan Lokasi</label>
                                <input type="text"
                                    wire:model="{{ $this->selected_post_state ? 'update_custom_address' : 'custom_address' }}"
                                    class="form-control" id="input1">
                                <div id="inputHelp" class="form-text">Masukkan nama lokasi secara kustom</div>
                                @error('custom_address')
                                    <span class="error text-danger">{{ $message }}</span>
                            @enderror
                                @error('update_custom_address')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" wire:click="resetFormCustomAddress()"
                                class="btn btn-outline-danger">
                                <div wire:loading wire:target="resetFormCustomAddress()"
                                    class="spinner-border spinner-border-sm" role="status">
                                </div>
                                Reset
                            </button>
                            <hr style="width: 80%">
                            <div id="inputHelp" class="form-text mt-3" style="width: 80%">Catatan: <br> Jika form
                                kustom lokasi masih terbuka, maka yang akan dimasukkan adalah data yang ada di form
                                kustom lokasi, bukan yang berada di opsi lokasi</div>
                        </div>
                    @endif
                </div>  --}}
            </div>

            {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        </div>
        @if (!$createCategoryState && !$createTagState)
            </form>
        @endif
    </div>
</div>

{{-- <script>
    function initializeCKEditor() {
       ClassicEditor
          .create(document.querySelector('#body'), {
             ckfinder: {
                uploadUrl: 'http://127.0.0.1:8000/image-upload?_token=UgcJRRoNleVEud8Em45rjCmPBH1B3yTPwyrqR8la',
             }
          })
          .then(editor => {
             MyEditor = editor;
             editor.model.document.on('change:data', () => {
                let body_content = editor.getData();
                Livewire.dispatch('body', {
                   data: body_content
                });
                Livewire.dispatch('body-updated', {
                   data: body_content
                });
             });
          })
          .catch(error => {
             console.error(error);
          });
    }
 
    setTimeout(initializeCKEditor, 1000); // Adjust the delay as needed
 </script> --}}
{{-- 
 <script>
    ClassicEditor
            .create(document.querySelector('#body'), {
                ckfinder: {
                    uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}"
                }
            })
            .then(editor => {
                MyEditor = editor;
                editor.model.document.on('change:data', () => {
                    // let body = document.getElementById('body').getAttribute('data-body')
                    // eval(body).set('body', document.getElementById('body').value)
                    // console.log(editor.getData())
                    let body_content = editor.getData()
                    Livewire.dispatch('body', {
                        data: body_content
                    })
                    Livewire.dispatch('body-updated', {
                        data: body_content
                    })
                });
                // editor.setData(contentBody)
            })
            .catch(error => {
                console.error(error);
            });
        Livewire.on("selected", (data) => {
            MyEditor.setData(data.data);
        })
        Livewire.on("reset-body", (data) => {
            MyEditor.setData(data.data);
        })
</script> --}}

<script src="{{ asset('assets/vendor/ckeditor5/build/ckeditor.js') }}"></script>
@script
    <script>
        ClassicEditor
            .create(document.querySelector('#body'), {

                fontSize: {
                    options: [
                        'default',
                        10,
                        11,
                        12,
                        13,
                        14,
                        15,
                        16,
                        17,
                        18,
                        19,
                        20,
                        25,
                    ]
                },
                // toolbar: [ 'resizeImage'],
                ckfinder: {
                    uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}"
                },
            })
            .then(editor => {
                MyEditor = editor;
                editor.model.document.on('change:data', () => {
                    // let body = document.getElementById('body').getAttribute('data-body')
                    // eval(body).set('body', document.getElementById('body').value)
                    // console.log(editor.getData())
                    let body_content = editor.getData()
                    Livewire.dispatch('body', {
                        data: body_content
                    })
                    Livewire.dispatch('body-updated', {
                        data: body_content
                    })
                });
                // editor.setData(contentBody)
            }).catch(error => {
                console.error(error);
            });
        Livewire.on("selected", (data) => {
            MyEditor.setData(data.data);
        })
        Livewire.on("reset-body", (data) => {
            MyEditor.setData(data.data);
        })
    </script>
@endscript
