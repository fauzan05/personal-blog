<div>
    <nav class="m-4 border shadow-sm rounded main-navbar d-flex flex-row justify-content-between">
        <div class="logo-wrapper ms-2">
            <a href="{{ url('/') }}">
                <ul class="no-padding">
                    <li>
                        <img class="logo" src="{{ asset('assets/logo/' . $logo) }}" alt="">
                    </li>
                    <li class="title">Admin Dashboard</li>
                </ul>
            </a>
        </div>
        <div class="menu-navbar">
            <ul class="menu-wrapper no-padding">
                <li><a class=" {{ $current_url == 'admin/dashboard' ? 'active' : '' }} "
                        href="{{ url('admin/dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                <li><a class=" {{ $current_url == 'admin/posts' ? 'active' : '' }} "
                        href="{{ url('admin/posts') }}">Postingan</a></li>
                <li><a class=" {{ $current_url == 'admin/settings' ? 'active' : '' }} "
                        href="{{ url('admin/settings') }}">Pengaturan</a></li>
                <li><a class=" {{ $current_url == 'admin/about' ? 'active' : '' }} "
                        href="{{ url('admin/about') }}">Tentang</a></li>
            </ul>
            <div class="dark-mode-wrapper ms-3" style="height: 80px; width: 10%">
                <div id="dark-mode-switch" class="mx-3">
                    <li><span class="material-symbols-outlined sun">light_mode</span></li>
                    <li><span class="material-symbols-outlined moon">dark_mode</span></li>
                </div>
            </div>
            <div class="d-flex flex-column mx-5 align-items-center justify-content-center" style="width: 10%;">
                    <div>
                        <a href="{{url('admin/logout')}}" >
                            <i id="logout-button" class="logout-button fa-solid fa-power-off mt-2" data-bs-toggle="tooltip"
                                data-bs-placement="bottom" data-bs-title="Logout">
                            </i></a>
                    </div> 
            </div>
        </div>
    </nav>
</div>
@script
    <script>
        let text_color;
        let navbar_color;
        Livewire.on('navbar-text-color', (data) => {
            text_color = data.data;
            document.body.style.setProperty('--navbar-text-color', text_color);
        })
        Livewire.on('navbar-color', (data) => {
            navbar_color = data.data;
            document.body.style.setProperty('--navbar-color', navbar_color);
        })
    </script>
@endscript
