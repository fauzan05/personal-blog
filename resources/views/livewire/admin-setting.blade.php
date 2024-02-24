<div>
    <div class="container-fluid dashboard-content position-relative z-2 ">
        <div class="d-flex flex-row justify-content-between align-items-center mb-4" style="width: 100%">
            <span class="admin-welcome mb-3">Menu Pengaturan &nbsp <i class="fa-solid fa-sliders"></i></span>
            @if (session('status_success'))
                <div class="alert alert-success" role="alert" style="auto">
                    {{ session('status_success')['message'] }}
                </div>
            @endif
        </div>
        <div class="container">
            <div class="row d-flex flex-row gap-5 justify-content-center align-items-start">
                <div class="col-lg-5">
                    <div class="row d-flex flex-column">
                        <div class="col-lg-12 mb-5 card shadow-sm">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="sub-title my-3 text-center">Pengaturan Pengguna Admin</span>
                                <hr>
                                <div class="d-flex flex-column align-items-center">
                                    <img class="rounded-circle mb-5"
                                        src="{{ asset('assets/user-profile-image/' . $profile_photo_filename) }}"
                                        alt="profile-photo" style="width: 100px; height:100px; object-fit:contain;">
                                    @if (session('status_error'))
                                        <div class="alert alert-{{ session('status_error')['color'] }}" role="alert"
                                            style="auto">
                                            {{ session('status_error')['message'] }}
                                        </div>
                                    @endif
                                    <form
                                        wire:submit="{{ $update_password_state ? 'updateUserPassword' : 'updateUserProfile' }}">
                                        <div class="row m-2">
                                            @if (!$update_password_state)
                                                <div class="mb-3 col-md-6">
                                                    <label for="first-name" class="form-label">Nama Depan</label>
                                                    <input type="text" wire:model="first_name" class="form-control"
                                                        id="first-name">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="last-name" class="form-label">Nama Belakang</label>
                                                    <input type="text" wire:model="last_name" class="form-control"
                                                        id="last-name">
                                                </div>
                                                <div class="input-group my-3">
                                                    <span class="input-group-text" id="basic-addon1">@</span>
                                                    <input type="email" wire:model="email" class="form-control"
                                                        placeholder="Email" aria-label="Email"
                                                        aria-describedby="basic-addon1">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="place-of-birth" class="form-label">Tempat Lahir</label>
                                                    <input type="text" wire:model="place_of_birth"
                                                        class="form-control" id="place-of-birth">
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="date-of-birth" class="form-label">Tanggal Lahir</label>
                                                    <input type="date" wire:model="date_of_birth"
                                                        class="form-control" id="date-of-birth">
                                                </div>
                                                <div class="col-md-6 mb-4" style="padding-top: 16px">
                                                    <div class="input-group my-3">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                class="fa-solid fa-phone"></i></span>
                                                        <input type="text" wire:model="phone_number"
                                                            class="form-control" placeholder="Nomor telepon"
                                                            aria-label="text" aria-describedby="basic-addon1">
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <fieldset disabled>
                                                        <label for="role" class="form-label">Role/Bagian</label>
                                                        <input type="text" id="role" wire:model="role"
                                                            class="form-control" placeholder="Role">
                                                    </fieldset>
                                                </div>
                                                <div class="mb-3 col-lg-12">
                                                    <label for="formFileSm" class="form-label">Unggah Foto
                                                        Profil</label>
                                                    <input class="form-control form-control-sm"
                                                        wire:model="update_profile_image" id="formFileSm"
                                                        type="file">
                                                    <div id="inputHelp" class="form-text">Min. 20kb Max. 20Mb. Format
                                                        gambar.
                                                    </div>
                                                    @error('update_profile_image')
                                                        <span class="error text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="bio" class="form-label">Bio</label>
                                                    <textarea class="form-control" wire:model="bio" id="bio" rows="3">{{ $bio }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="old_password" class="form-label">Verifikasi Password
                                                        Lama</label>
                                                    <input type="password" wire:model="old_password"
                                                        class="form-control" id="old_password">
                                                    @error('old_password')
                                                        <span class="error text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @elseif($update_password_state)
                                                <div class="mb-3">
                                                    <label for="old_password" class="form-label">Masukkan Password
                                                        Lama</label>
                                                    <input type="password" wire:model="old_password"
                                                        class="form-control" id="old_password">
                                                    @error('old_password')
                                                        <span class="error text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_password" class="form-label">Masukkan Password
                                                        Baru</label>
                                                    <input type="password" wire:model="new_password"
                                                        class="form-control" id="new_password">
                                                    @error('new_password')
                                                        <span class="error text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_password_confirmation"
                                                        class="form-label">Konfirmasi
                                                        Password Baru</label>
                                                    <input type="password" wire:model="new_password_confirmation"
                                                        class="form-control" id="new_password_confirmation">
                                                    @error('new_password_confirmation')
                                                        <span class="error text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                            <div class="col-lg-12 my-3 d-flex flex-column align-items-center">
                                                @if ($update_password_state)
                                                    <button type="submit" wire:click="updateUserPassword()"
                                                        class="btn btn-outline-primary mb-2" style="width: 100%">
                                                        <div wire:loading wire:target="updateUserPassword()"
                                                            class="spinner-border spinner-border-sm" role="status">
                                                        </div>
                                                        Perbarui Password
                                                    </button>
                                                    <a href="#" wire:click.prevent="updateUserPasswordState()">
                                                        Perbarui Profil Pengguna
                                                    </a>
                                                @elseif(!$update_password_state)
                                                    <button type="submit" wire:click="updateUserProfileState()"
                                                        class="btn btn-outline-primary mb-2" style="width: 100%">
                                                        <div wire:loading wire:target="updateUserProfileState()"
                                                            class="spinner-border spinner-border-sm" role="status">
                                                        </div>
                                                        Simpan Profil
                                                    </button>
                                                    <a href="#" wire:click.prevent="updateUserPasswordState()">
                                                        Perbarui Password
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 card shadow-sm">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="sub-title text-center my-3">Daftar Akun Media Sosial</span>
                                <hr>
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    @if (session('status_social_media'))
                                        <div class="alert alert-{{ session('status_social_media')['color'] }}"
                                            role="alert" style="auto">
                                            {{ session('status_social_media')['message'] }}
                                        </div>
                                    @endif
                                    @if (empty($social_medias))
                                        <div id="inputHelp" class="form-text text-center my-5 ms-1">Sosial Media kosong.
                                            Silahkan
                                            buat.</div>
                                    @endif
                                    {{-- @elseif(!empty($social_medias)) --}}
                                        @if ($create_social_media_state)
                                            <div>
                                                <form wire:submit="createSocialMedia" action="">
                                                    <div class="row d-flex flex-column justify-content-center">
                                                        <div class="input-group input-group-sm mb-3 col-lg-8">
                                                            <span class="input-group-text"
                                                                id="inputGroup-sizing-sm">Nama
                                                                Akun</span>
                                                            <input type="text" wire:model="account_name"
                                                                class="form-control" aria-label="Sizing example input"
                                                                aria-describedby="inputGroup-sizing-sm">
                                                        </div>
                                                        @error('account_name')
                                                            <span
                                                                class="error text-danger mb-3">{{ $message }}</span>
                                                        @enderror
                                                        <div class="input-group input-group-sm mb-3 col-lg-8">
                                                            <span class="input-group-text"
                                                                id="inputGroup-sizing-sm">Link
                                                                Akun</span>
                                                            <input type="text" wire:model="account_link"
                                                                class="form-control" aria-label="Sizing example input"
                                                                aria-describedby="inputGroup-sizing-sm">
                                                        </div>
                                                        @error('account_link')
                                                            <span
                                                                class="error text-danger mb-3">{{ $message }}</span>
                                                        @enderror
                                                        <div class="col-lg-12 mb-3">
                                                            <select wire:model="account_type"
                                                                class="form-select form-select-sm"
                                                                aria-label="Small select example">
                                                                <option value="" disabled selected>Pilih tipe
                                                                </option>
                                                                <option value="instagram">Instagram</option>
                                                                <option value="facebook">Facebook</option>
                                                                <option value="telegram">Telegram</option>
                                                                <option value="twitter">Twitter</option>
                                                                <option value="discord">Discord</option>
                                                            </select>
                                                        </div>
                                                        @error('account_type')
                                                            <span
                                                                class="error text-danger mb-3">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                            </div>
                                        @elseif($edit_social_media_state)
                                            @foreach ($social_medias as $key => $social_media)
                                                <div class="d-flex flex-column my-2 justify-content-center rounded border"
                                                    style="width: 95%">
                                                    <div class="d-flex my-2 mx-2 flex-row justify-content-between">
                                                        <div
                                                            class="d-flex ms-2 me-3 flex-column justify-content-center align-items-center">
                                                            <i class="fa-brands fa-{{ $social_media['type'] }}"
                                                                style="font-size: 2.5rem; color: var(--text-color)"></i>
                                                        </div>
                                                        <div class="d-flex mx-1 flex-row gap-3">
                                                            <input class="form-control form-control-sm"
                                                                wire:model="edit_account_name.{{ $social_media['id'] }}"
                                                                type="text"
                                                                placeholder="{{ $social_media['account_name'] }}"
                                                                aria-label=".form-control-sm example">
                                                            <input class="form-control form-control-sm"
                                                                wire:model="edit_account_link.{{ $social_media['id'] }}"
                                                                type="text"
                                                                placeholder="{{ $social_media['account_link'] }}"
                                                                aria-label=".form-control-sm example">
                                                            <select
                                                                wire:model="edit_account_type.{{ $social_media['id'] }}"
                                                                class="form-select form-select-sm"
                                                                aria-label="Small select example">
                                                                <option value="" disabled selected>Pilih tipe
                                                                </option>
                                                                <option value="instagram">Instagram</option>
                                                                <option value="facebook">Facebook</option>
                                                                <option value="telegram">Telegram</option>
                                                                <option value="twitter">Twitter</option>
                                                                <option value="discord">Discord</option>
                                                            </select>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-row justify-content-center align-items-center">
                                                            <div class="ms-3">
                                                                <button type="button"
                                                                    wire:click="deleteSocialMedia({{ $social_media['id'] }})"
                                                                    class="btn btn-danger d-flex flex-row"
                                                                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                                    <div wire:loading
                                                                        wire:target="deleteSocialMedia({{ $social_media['id'] }})"
                                                                        class="spinner-border spinner-border-sm me-2"
                                                                        role="status"></div>
                                                                    <i class="fa-solid fa-delete-left"></i>
                                                                </button>
                                                            </div>
                                                            <div class="ms-3">
                                                                <button type="button"
                                                                    wire:click="editSocialMedia({{ $social_media['id'] }})"
                                                                    class="btn btn-success d-flex flex-row"
                                                                    style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                                    <div wire:loading
                                                                        wire:target="editSocialMedia({{ $social_media['id'] }})"
                                                                        class="spinner-border spinner-border-sm me-2"
                                                                        role="status"></div>
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if(!$create_social_media_state && !$edit_social_media_state && !empty($social_medias))
                                            @foreach ($social_medias as $key => $social_media)
                                                <div class="d-flex flex-column my-2 justify-content-center rounded border"
                                                    style="width: 95%">
                                                    <div class="d-flex my-2 mx-2 flex-row justify-content-start">
                                                        <div
                                                            class="d-flex ms-2 me-3 flex-column justify-content-center align-items-center">
                                                            <i class="fa-brands fa-{{ $social_media['type'] == 'twitter' ? 'x-twitter' : $social_media['type'] }}"
                                                                style="font-size: 2.5rem; color: var(--text-color)"></i>
                                                        </div>
                                                        <div class="d-flex mx-1 flex-column gap-1">
                                                            <span style="font-weight: 1000">Nama Akun :
                                                                {{ $social_media['account_name'] }}</span>
                                                            <span style="width: 200px">Link : <a
                                                                    href="{{ $social_media['account_link'] }}">{{ $social_media['account_link'] }}
                                                                </a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                    <div class="d-flex flex-row gap-4 my-3">
                                        <button type="button" wire:click="createSocialMediaState()"
                                            class="btn btn-outline-{{ $create_social_media_state ? 'dark' : 'primary' }} my-2">
                                            <div wire:loading wire:target="createSocialMediaState()"
                                                class="spinner-border spinner-border-sm" role="status"></div>
                                            {{ !$create_social_media_state ? 'Buat Media Sosial' : 'Tutup' }}
                                        </button>
                                        @if ((!$create_social_media_state && !$edit_social_media_state) || $edit_social_media_state)
                                            <button type="button" wire:click="editSocialMediaState()"
                                                class="btn btn-outline-{{ $edit_social_media_state ? 'dark' : 'success' }} my-2">
                                                <div wire:loading wire:target="editSocialMediaState()"
                                                    class="spinner-border spinner-border-sm" role="status"></div>
                                                {{ !$edit_social_media_state ? 'Edit Media Sosial' : 'Tutup' }}
                                            </button>
                                        @elseif($create_social_media_state && !$edit_social_media_state)
                                            <button type="submit" wire:click="createSocialMedia()"
                                                class="btn btn-outline-primary my-2">
                                                <div wire:loading wire:target="createSocialMedia()"
                                                    class="spinner-border spinner-border-sm" role="status"></div>
                                                Buat Media Sosial
                                            </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row d-flex flex-column">
                        <div class="col-lg-12 mb-5 card shadow-sm">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="sub-title text-center my-3">Pengaturan Aplikasi</span>
                                <hr>
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <img class="rounded-circle mb-5"
                                        src="{{ asset('assets/logo/' . $logo_filename) }}" alt="profile-photo"
                                        style="width: 100px; height:100px; object-fit:contain; ">
                                    <form wire:submit="updateAppSettings">
                                        <div class="row d-flex justify-content-around">
                                            <div class="mb-4 col-md-8">
                                                <label for="blog-name" class="form-label">Nama Blog</label>
                                                <input type="text" wire:model="blog_name" class="form-control"
                                                    id="blog-name">
                                            </div>
                                            <div class="col-md-12 mb-3 form-check form-switch d-flex flex-row justify-content-center">
                                                <input class="form-check-input" wire:model="show_title_state" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                                                <label class="form-check-label" for="flexSwitchCheckChecked"> &nbsp Tampilkan Nama Blog Di Navbar</label>
                                              </div>
                                            <div class="col-md-8">
                                                <div class="input-group my-3">
                                                    <span class="input-group-text" id="basic-addon1">@</span>
                                                    <input type="email" wire:model="email_blog"
                                                        class="form-control" placeholder="Email blog"
                                                        aria-label="Email" aria-describedby="basic-addon1">
                                                </div>
                                                @error('email_blog')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-8 mb-4">
                                                <div class="input-group my-3">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="fa-solid fa-phone"></i></span>
                                                    <input type="text" wire:model="phone_number_blog"
                                                        class="form-control" placeholder="Kontak blog"
                                                        aria-label="text" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-6 d-flex flex-column mb-3 align-items-center justify-content-center">
                                                <label for="navbar-color" class="form-label text-center">Warna
                                                    Navbar</label>
                                                <input type="color" wire:model="navbar_color"
                                                    class="form-control form-control-color" id="navbar-color"
                                                    style="width: 80%">
                                            </div>
                                            <div
                                                class="col-md-6 d-flex flex-column mb-3 align-items-center justify-content-center">
                                                <label for="footer-color" class="form-label text-center">Warna
                                                    Footer</label>
                                                <input type="color" wire:model="footer_color"
                                                    class="form-control form-control-color" id="footer-color"
                                                    style="width: 80%">
                                            </div>
                                            <div
                                                class="col-md-6 d-flex flex-column mb-3 align-items-center justify-content-center">
                                                <label for="navbar-text-color" class="form-label text-center">Warna
                                                    Teks Navbar</label>
                                                <input type="color" wire:model="navbar_text_color"
                                                    class="form-control form-control-color" id="navbar-text-color"
                                                    style="width: 80%">
                                            </div>
                                            <div
                                                class="col-md-6 d-flex flex-column mb-3 align-items-center justify-content-center">
                                                <label for="footer-text-color" class="form-label text-center">Warna
                                                    Teks Footer</label>
                                                <input type="color" wire:model="footer_text_color"
                                                    class="form-control form-control-color" id="footer-text-color"
                                                    style="width: 80%">
                                            </div>
                                            <div class="my-3 col-lg-8">
                                                <label for="logo" class="form-label">Unggah Logo</label>
                                                <input class="form-control form-control-sm"
                                                    wire:model="update_logo_image" id="logo" type="file">
                                                <div id="inputHelp" class="form-text">Min. 20kb Max. 20Mb. Format
                                                    gambar.</div>
                                                @error('update_logo_image')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-10 my-3 d-flex flex-column align-items-center">
                                                <button type="submit" wire:click="updateAppSettingsState()"
                                                    class="btn btn-outline-primary mb-2">
                                                    <div wire:loading wire:target="updateAppSettingsState()"
                                                        class="spinner-border spinner-border-sm" role="status"></div>
                                                    Simpan Pengaturan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 card shadow-sm mb-5">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="sub-title text-center my-3">Daftar Alamat</span>
                                <hr>
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    @if (session('status_address'))
                                        <div class="alert alert-success" role="alert" style="auto">
                                            {{ session('status_address') }}
                                        </div>
                                    @endif
                                    @if ($create_address_state)
                                        <form wire:submit="createNewAddress" action="">
                                            <div class="mb-3 col-md-12">
                                                <label for="province" class="form-label">Provinsi</label>
                                                <input type="text" wire:model="province" class="form-control"
                                                    id="province">
                                            </div>
                                            <div class="mb-5 col-md-12">
                                                <label for="city" class="form-label">Negara</label>
                                                <input type="text" wire:model="country" class="form-control"
                                                    id="city">
                                            </div>
                                            <div
                                                class="col-lg-12 my-3 d-flex flex-row align-items-center justify-content-center">
                                                <button type="submit" wire:click="createAddress()"
                                                    class="btn btn-outline-primary mb-2">
                                                    <div wire:loading wire:target="createAddress()"
                                                        class="spinner-border spinner-border-sm" role="status">
                                                    </div>
                                                    Buat Alamat
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                    @if (!empty($addresses))
                                        @if ($edit_address_state)
                                            @foreach ($addresses as $key => $address)
                                                <div class="d-flex flex-row align-items-center justify-content-center"
                                                    style="width: 40%">
                                                    <div class="my-2 me-2 col-md-6">
                                                        <input type="text"
                                                            wire:model="edit_selected_province_address.{{ $address['id'] }}"
                                                            class="form-control" id="city"
                                                            placeholder="{{ $address['province'] }}">
                                                    </div>
                                                    <div class="my-2 col-md-6">
                                                        <input type="text"
                                                            wire:model="edit_selected_country_address.{{ $address['id'] }}"
                                                            class="form-control" id="city"
                                                            placeholder="{{ $address['country'] }}">
                                                    </div>
                                                    <div class="ms-3">
                                                        <button type="button"
                                                            wire:click="deleteAddress({{ $address['id'] }})"
                                                            class="btn btn-danger"
                                                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                            <div wire:loading
                                                                wire:target="deleteAddress({{ $address['id'] }})"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status"></div>
                                                            <i class="fa-solid fa-delete-left"></i>
                                                        </button>
                                                    </div>
                                                    <div class="ms-3">
                                                        <button type="button"
                                                            wire:click="editAddress({{ $key }})"
                                                            class="btn btn-success"
                                                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                            <div wire:loading
                                                                wire:target="editAddress({{ $key }})"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status"></div>
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                    </div>
                                                    <div class="ms-3 d-flex flex-row">
                                                        <button type="button"
                                                            wire:click="setMainAddress({{ $address['id'] }})"
                                                            class="btn btn-primary"
                                                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1rem; --bs-btn-font-size: .75rem;">
                                                            <div wire:loading
                                                                wire:target="setMainAddress({{ $address['id'] }})"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status"></div>
                                                            <i class="fa-solid fa-arrow-left"></i>
                                                            Set Utama

                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @elseif((!$edit_address_state && $create_address_state) || !$create_address_state)
                                            @foreach ($addresses as $key => $address)
                                                <div class="d-flex flex-column align-items-center justify-content-center my-2"
                                                    style="width: 100%">
                                                    {{-- {{var_dump($selected_address)}} --}}
                                                    @if ($address['is_active'])
                                                        <div class="form-check d-flex flex-row">
                                                            <div
                                                                class="check-icon d-flex align-items-center justify-content-center">
                                                                <i class="fa-solid fa-check"></i>
                                                            </div>
                                                            &nbsp &nbsp
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault{{ $key + 1 }}">
                                                                {{ $address['province'] . ', ' . $address['country'] }}
                                                            </label>
                                                        </div>
                                                    @else
                                                        <div class="form-check">
                                                            <label class="form-check-label"
                                                                for="flexRadioDefault{{ $key + 1 }}">
                                                                {{ $address['province'] . ', ' . $address['country'] }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    @elseif(empty($addresses))
                                        @if (!$create_address_state)
                                            <div id="inputHelp" class="form-text my-5">Alamat/Lokasi kosong.
                                                Silahkan
                                                buat.</div>
                                        @endif
                                    @endif
                                    <div
                                        class="col-lg-12 my-3 d-flex flex-row align-items-center justify-content-center">
                                        <button type="button" wire:click="createAddressState()"
                                            class="btn btn-outline-{{ $create_address_state ? 'dark' : 'primary' }} mb-2">
                                            <div wire:loading wire:target="createAddressState()"
                                                class="spinner-border spinner-border-sm" role="status"></div>
                                            {{ !$create_address_state ? 'Buat Alamat' : 'Tutup' }}
                                        </button>
                                        @if (!$create_address_state)
                                            <button type="button" wire:click="editAddressState()"
                                                class="btn btn-outline-success mb-2 ms-3">
                                                <div wire:loading wire:target="editAddressState()"
                                                    class="spinner-border spinner-border-sm" role="status"></div>
                                                {{ !$edit_address_state ? 'Edit Alamat' : 'Tutup' }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 card shadow-sm mb-5">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="sub-title text-center my-3">Daftar Menu</span>
                                <hr>
                                <div class="d-flex flex-column align-items-center justify-content-center"
                                    style="width: 100%">
                                    @if (session('status_menu'))
                                        <div class="alert alert-{{ session('status_menu')['color'] }}" role="alert"
                                            style="auto">
                                            {{ session('status_menu')['message'] }}
                                        </div>
                                    @endif
                                    @if (empty($menus))
                                        <div id="inputHelp" class="form-text text-center my-5">Menu kosong.
                                            Silahkan
                                            buat.</div>
                                    @endif
                                        @if ($create_menu_state)
                                            <div class="d-flex flex-column mb-3 align-items-start">
                                                <label for="create-menu" class="form-label">Nama Menu</label>
                                                <input wire:model="menu" wire:keydown.enter="createMenu()" wire:keydown.escape="createMenuState()" class="form-control form-control-sm"
                                                    type="text" placeholder="Buat menu"
                                                    aria-label=".form-control-sm example">
                                            </div>
                                            <button type="button" wire:click="createMenu()"
                                                class="btn btn-outline-primary my-2">
                                                <div wire:loading wire:target="createMenu()"
                                                    class="spinner-border spinner-border-sm" role="status"></div>
                                                Buat Menu
                                            </button>
                                        @elseif($edit_menu_state)
                                            @foreach ($menus as $key => $menu)
                                                <div class="d-flex flex-row gap-1">
                                                    <div class="mb-3">
                                                        <input wire:model="edit_menu.{{ $menu['id'] }}"
                                                        wire:keydown.enter="editMenu({{$key}})" wire:keydown.escape="editMenuState()"
                                                         class="form-control form-control-sm" type="text"
                                                            placeholder="{{ $menu['name'] }}"
                                                            aria-label=".form-control-sm example">
                                                    </div>
                                                    <div class="ms-3">
                                                        <button type="button"
                                                            wire:click="deleteMenu({{ $menu['id'] }}, '{{ $menu['name'] }}')"
                                                            class="btn btn-danger"
                                                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                            <div wire:loading
                                                                wire:target="deleteMenu({{ $menu['id'] }}, '{{ $menu['name'] }}')"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status"></div>
                                                            <i class="fa-solid fa-delete-left"></i>
                                                        </button>
                                                    </div>
                                                    <div class="ms-3">
                                                        <button type="button"
                                                            wire:click="editMenu({{ $key }})"
                                                            class="btn btn-success"
                                                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                                                            <div wire:loading
                                                                wire:target="editMenu({{ $key }})"
                                                                class="spinner-border spinner-border-sm"
                                                                role="status"></div>
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if(!empty($menus) && !$edit_menu_state && !$create_menu_state)
                                            @foreach ($menus as $key => $menu)
                                                <div class="d-flex flex-column align-items-center gap-3 border rounded"
                                                    style="width: 50%">
                                                    <div class="d-flex ms-3 flex-row align-items-center justify-content-center"
                                                        style="width: 100%">
                                                        <div class="check-icon d-flex align-items-center justify-content-center"
                                                            style="flex: 0 0 auto;">
                                                            <i class="fa-solid fa-check"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <label class="ms-3 my-3"
                                                                for="">{{ $menu['name'] }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    <div class="d-flex flex-row gap-4 my-3">
                                        <button type="button" wire:click="createMenuState()"
                                            class="btn btn-outline-{{ $create_menu_state ? 'dark' : 'primary' }} my-2">
                                            <div wire:loading wire:target="createMenuState()"
                                                class="spinner-border spinner-border-sm" role="status"></div>
                                            {{ !$create_menu_state ? 'Buat Menu' : 'Tutup' }}
                                        </button>
                                        <button type="button" wire:click="editMenuState()"
                                            class="btn btn-outline-{{ $edit_menu_state ? 'dark' : 'success' }} my-2">
                                            <div wire:loading wire:target="editMenuState()"
                                                class="spinner-border spinner-border-sm" role="status"></div>
                                            {{ !$edit_menu_state ? 'Edit Menu' : 'Tutup' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
