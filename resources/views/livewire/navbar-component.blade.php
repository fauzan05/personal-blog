<div>
    <nav class="shadow main-navbar d-flex flex-row justify-content-between">
        <div>
            <ul class="d-flex gap-3 align-items-center justify-content-center" style="width: auto">
                <li>
                    <img class="logo" src="{{ asset('assets/logo/' . $logo) }}" alt="">
                <li class="title">{{ $website_name }}</li>
                </li>
            </ul>
        </div>
        <div class="d-flex align-items-start justify-content-around">
            <ul class="d-flex align-items-center justify-content-around">
                <li class="mx-5"><a class="{{ (bool) Request::is('about') ? 'active' : '' }}"
                        href="{{ url('/about') }}">About</a></li>
                @foreach ($menus as $menu)
                    <li class="mx-5"><a class="{{ $current_url == strtolower($menu['name']) ? 'active' : '' }} "
                            href="{{ url('/' . strtolower($menu['name'])) }}">{{ $menu['name'] }}</a></li>
                @endforeach
                <ul class="d-flex ms-3 flex-column dark-mode align-items-center justify-content-center" style="width: auto;">
                    <div id="dark-mode-switch">
                        <li><span class="material-symbols-outlined sun">
                                light_mode
                            </span></li>
                        <li><span class="material-symbols-outlined moon">
                                dark_mode
                            </span></li>
                    </div>
                </ul>
                <li class="d-flex m-5 dark-mode flex-column align-items-center justify-content-center" style="width: auto;">
                    <i class="search fa-solid fa-magnifying-glass"></i>
                </li>
            </ul>
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
