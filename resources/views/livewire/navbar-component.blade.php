<div>
    <nav class="shadow main-navbar d-flex flex-row align-items-center justify-content-between">
        <div class="logo-wrapper ms-2">
            <a href="{{ url('/') }}">
                <ul class="no-padding">
                    <li>
                        <img class="logo" src="{{ asset('assets/logo/' . $logo) }}" alt="">
                    </li>
                    <li class="title" style="display: {{ $show_blog_name ? 'block' : 'none' }}">{{ $website_name }}</li>
                </ul>
            </a>
        </div>
        <div class="menu-navbar">
            <div class="hamburger-menu-wrapper">
                <i id="show-sidebar" class=" hamburger-menu fa-solid fa-bars"></i>
            </div>
            <ul class="menu-wrapper no-padding">
                <li class=""><a class="{{ $current_url == 'about' ? 'active' : '' }}"
                        href="{{ url('/about') }}">About</a></li>
                @foreach ($menus as $menu)
                    <li class=""><a class="{{ $current_url == strtolower($menu['name']) ? 'active' : '' }}"
                            href="{{ url('/' . strtolower($menu['name'])) }}">{{ $menu['name'] }}</a></li>
                @endforeach
            </ul>
            <div class="dark-mode-wrapper ms-3" style="height: 80px; width: 10%">
                <div id="dark-mode-switch" class="mx-3">
                    <li><span class="material-symbols-outlined sun">light_mode</span></li>
                    <li><span class="material-symbols-outlined moon">dark_mode</span></li>
                </div>
            </div>
            <div class="search-wrapper d-flex flex-column align-items-center justify-content-center me-3"
                style="height: 80px; width: 80% !important">
                <i id="searchPost" class="search fa-solid fa-magnifying-glass mx-3"></i>
            </div>
        </div>
    </nav>
    <div class="search-form border" id="searchForm">
        <input wire:model="postTitle" wire:keydown.enter="searchPosts()"
        class="form-control form-control-sm m-2" type="text" placeholder="Cari..."
        aria-label=".form-control-sm example"
         >
    </div>
    <div class="sidebar" style="left: {{$show_sidebar_state ? '40% !important;' : '100% !important;'}}">
        <div class="mt-3 dark-mode-wrapper-sidebar" style="height: auto; width: 80%">
            <div id="dark-mode-switch-sidebar" class="">
                <li><span class="sun-sidebar material-symbols-outlined sun">light_mode</span></li>
                <li><span class="moon-sidebar material-symbols-outlined moon">dark_mode</span></li>
                <li class="ms-4 pt-1" style="color: var(--navbar-text-color);">Mode</li>
            </div>
        </div>
        <div class="mt-4">
            <input wire:model="postTitle" wire:keydown.enter="searchPosts()" class="form-control form-control-sm search-input" type="text" placeholder="Cari..."
                aria-label=".form-control-sm example">
        </div>
        <hr style="width: 80%; color: var(--navbar-text-color)">
        <ul class="menu-wrapper-sidebar no-padding pt-4 pe-4">
            <li class=""><a class="{{ $current_url == 'about' ? 'active' : '' }}"
                    href="{{ url('/about') }}">About</a></li>
            @foreach ($menus as $menu)
                <li class=""><a class="{{ $current_url == strtolower($menu['name']) ? 'active' : '' }}"
                        href="{{ url('/' . strtolower($menu['name'])) }}">{{ $menu['name'] }}</a></li>
            @endforeach
        </ul>
    </div>
</div>

@script
    <script>
        Livewire.on('navbar-text-color', (data) => {
            text_color = data.data;
            document.body.style.setProperty('--navbar-text-color', text_color);
        })
        Livewire.on('navbar-color', (data) => {
            navbar_color = data.data;
            document.body.style.setProperty('--navbar-color', navbar_color);
        })
        let show_sidebar_state = false;
        let dark_mode_wrapper_sidebar = document.querySelector(".dark-mode-wrapper-sidebar")
        let body_content = document.querySelector(".body-content");
        let footer = document.querySelector("footer");
        let show_sidebar = document.getElementById("show-sidebar")
        let sidebar = document.querySelector(".sidebar")
        let text_color;
        let navbar_color;
        

        
        show_sidebar.addEventListener("click", () => {
            if (show_sidebar_state) {
                show_sidebar_state = false;
                // sidebar.style.opacity = "0";
                sidebar.style.left = "100%";
                footer.style.filter = "blur(0px)"
                body_content.style.filter = "blur(0px)"
                // dark_mode_wrapper_sidebar.style.display = "none"
            } else {
                show_sidebar_state = true;
                sidebar.style.left = "40%";
                // sidebar.style.opacity = "1";
                body_content.style.filter = "blur(5px)"
                footer.style.filter = "blur(5px)"
                // dark_mode_wrapper_sidebar.style.display = "flex"
                body_content.addEventListener("click", () => {
                    show_sidebar_state = false;
                    sidebar.style.left = "100%";
                    footer.style.filter = "blur(0px)"
                    body_content.style.filter = "blur(0px)"
                    // dark_mode_wrapper_sidebar.style.display = "none"
                })
            }
        });

        let searchPost = document.getElementById("searchPost")
        let searchPostState = false;
        let searchForm = document.getElementById("searchForm")
        searchPost.addEventListener("click", () => {
            if(searchPostState) {
                searchPostState = false;
                searchForm.classList.remove('show'); // Menambahkan kelas 'show' setelah jeda
                searchForm.style.display = "none";
            }else{
                searchPostState = true;
                setTimeout(function () {
                    searchForm.classList.toggle('show'); // Menambahkan kelas 'show' setelah jeda
        }, 200);
                searchForm.style.display = "flex";
                body_content.addEventListener("click", () => {
                    searchPostState = false;
                searchForm.classList.remove('show'); // Menambahkan kelas 'show' setelah jeda
                searchForm.style.display = "none";
            })
            }
        })
            
    </script>
@endscript
