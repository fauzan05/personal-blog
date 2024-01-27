<div class="col-lg-12 mb-5 d-flex flex-column justify-content-center align-items-center card"
                    style="width: 100%;">
                    <span class="choose-tags my-2">Pilih Tag</span>
                    {{var_dump($selected_post_id)}}
                    <hr class="no-padding" style="width: 80%">
                    @if (session('update_tag_status'))
                        <div class="d-flex flex-column mt-3 text-center justify-content-center align-items-center"
                            style="width: 80%">
                            <div class="alert alert-success gap-3 d-flex flex-column" role="alert">
                                {{ session('update_tag_status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                    <div class="tags-collection m-3 d-flex flex-column justify-content-start align-items-start">
                        @if (empty($tags))
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div id="inputHelp" class="form-text">Tags kosong/belum dibuat</div>
                                @if($configState)
                                @error('update_current_tag')
                                <span class="error text-danger text-center">{{ $message }}</span>
                            @enderror
                                @endif
                                @error('selected_tag')
                                    <span class="error text-danger text-center">{{ $message }}</span>
                                @enderror
                            </div>
                        @elseif(!empty($tags))
                            {{-- Jika Config False --}}
                            @if (!$configState)
                                @foreach ($tags as $key => $tag)
                                    <div class="d-flex flex-row justify-content-between gap-3 align-items-center my-1"
                                        style="width: 100%">
                                        <div class="form-check">
                                            @if (!$optionTagState)
                                                <input class="form-check-input"
                                                    wire:model="selected_tag.{{ $tag['id'] }}" type="checkbox"
                                                    id="flexCheckDefault{{ $tag['id'] }}"
                                                    style="transition: var(--tran-05)">
                                                <label class="form-check-label"
                                                    for="flexCheckDefault{{ $tag['id'] }}">
                                                    {{ $tag['name'] }}
                                                @elseif($optionTagState)
                                                    <div class="d-flex flex-row option justify-content-between">
                                                        <input wire:model="update_tag.{{ $tag['id'] }}"
                                                            class="form-control form-control-sm" type="text"
                                                            placeholder="{{ $tag['name'] }}"
                                                            aria-label=".form-control-sm example">
                                                        <div
                                                            class="d-flex flex-row ms-2 gap-2 justify-content-center align-items-center">
                                                            {{-- {{var_dump($tag['post'])}} --}}
                                                            @if (!$tag['post'])
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
                                                                wire:click="editTag('{{ $tag['name'] }}')"
                                                                class="btn btn-success save-tag btn-sm d-flex flex-row">
                                                                <i class="fa-solid fa-check"></i>
                                                                &nbsp
                                                                <div wire:loading
                                                                    wire:target="editTag('{{ $tag['name'] }}')"
                                                                    class="spinner-border spinner-border-sm"
                                                                    role="status">
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
                            @elseif($configState)
                                {{-- Jika Config True --}}
                                @if ($selected_post_state)
                                    @foreach ($tags as $key1 => $tag)
                                        @if (!$optionTagState)
                                            @php
                                                $tagPrinted = false;
                                            @endphp
                                            @foreach ($update_current_tag as $key2 => $current_tag)
                                                @if ($tag['id'] == $current_tag['id'])
                                                    <div class="d-flex flex-row justify-content-between gap-3 align-items-center my-1"
                                                        style="width: 100%">
                                                        <div class="form-check">
                                                            <input class="form-check-input"
                                                                wire:model="update_current_tag.{{ $key2 }}"
                                                                type="checkbox"
                                                                id="flexCheckDefault{{ $key2 }}"
                                                                style="transition: var(--tran-05)" checked>
                                                            <label class="form-check-label"
                                                                for="flexCheckDefault{{ $key2 }}">
                                                                {{ $current_tag['name'] }}
                                                        </div>
                                                    </div>
                                                    @php
                                                        $tagPrinted = true;
                                                    @endphp
                                                @endif
                                            @endforeach
                                            {{-- Ketika yang di current_tag sudah di print semua, tinggal print yang tidak ada di current_page --}}
                                            @if (!$tagPrinted)
                                                <div class="d-flex flex-row justify-content-between gap-3 align-items-center my-1"
                                                    style="width: 100%">
                                                    <div class="form-check">
                                                        <input class="form-check-input"
                                                            wire:model="update_current_tag.{{ $tag['id'] }}"
                                                            type="checkbox" id="flexCheckDefault{{ $tag['id'] }}"
                                                            style="transition: var(--tran-05)">
                                                        <label class="form-check-label"
                                                            for="flexCheckDefault{{ $tag['id'] }}">
                                                            {{ $tag['name'] }}
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif($optionTagState)
                                            <div
                                                class="d-flex flex-row justify-content-center gap-3 align-items-center  my-1">
                                                <div class="form-check">
                                                    <input wire:model="update_tag.{{ $tag['id'] }}"
                                                        class="form-control form-control-sm" type="text"
                                                        placeholder="{{ $tag['name'] }}"
                                                        aria-label=".form-control-sm example">
                                                </div>

                                                <div
                                                    class="d-flex flex-row gap-2 justify-content-center align-items-center">
                                                    {{-- {{var_dump($tag['post'])}} --}}
                                                    @if (!$tag['post'])
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
                                                        wire:click="editTag('{{ $tag['name'] }}')"
                                                        class="btn btn-success save-tag btn-sm d-flex flex-row">
                                                        <i class="fa-solid fa-check"></i>
                                                        &nbsp
                                                        <div wire:loading wire:target="editTag('{{ $tag['name'] }}')"
                                                            class="spinner-border spinner-border-sm" role="status">
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @elseif(!$selected_post_state)
                                    @foreach ($tags as $key => $tag)
                                        @if (!$optionTagState)
                                            <div class="d-flex flex-row justify-content-between gap-3 align-items-center my-1"
                                                style="width: 100%">
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        wire:model="selected_tag.{{ $tag['id'] }}" type="checkbox"
                                                        id="flexCheckDefault{{ $tag['id'] }}"
                                                        style="transition: var(--tran-05)">
                                                    <label class="form-check-label"
                                                        for="flexCheckDefault{{ $tag['id'] }}">
                                                        {{ $tag['name'] }}
                                                </div>
                                            </div>
                                        @elseif($optionTagState)
                                            <div
                                                class="d-flex flex-row justify-content-center gap-3 align-items-center  my-1">
                                                <div class="form-check">
                                                    <input wire:model="update_tag.{{ $tag['id'] }}"
                                                        class="form-control form-control-sm" type="text"
                                                        placeholder="{{ $tag['name'] }}"
                                                        aria-label=".form-control-sm example">
                                                </div>

                                                <div
                                                    class="d-flex flex-row gap-2 justify-content-center align-items-center">
                                                    {{-- {{var_dump($tag['post'])}} --}}
                                                    @if (!$tag['post'])
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
                                                        wire:click="editTag('{{ $tag['name'] }}')"
                                                        class="btn btn-success save-tag btn-sm d-flex flex-row">
                                                        <i class="fa-solid fa-check"></i>
                                                        &nbsp
                                                        <div wire:loading wire:target="editTag('{{ $tag['name'] }}')"
                                                            class="spinner-border spinner-border-sm" role="status">
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                        @endif
                    </div>
                    <div class="d-flex flex-row gap-3 my-3">
                        <button type="button" wire:click="setCreateTagState()"
                            class="btn btn-outline-{{ (bool) $darkModeState === true ? 'light' : 'dark' }}">
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
                    <div id="inputHelp" class="form-text my-3" style="width: 80%">Catatan: <br> Tag yang sudah
                        dipakai di post lain tidak bisa dihapus. Nama Tag tidak boleh sama.</div>
                    @if ($createTagState)
                        </form>
                    @endif
                    @if (session('create_tag_status'))
                        <div class="alert alert-success mt-3 text-center" role="alert" style="width: 80%">
                            {{ session('create_tag_status') }}
                        </div>
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
