<div>
    <footer
        class="row row-cols-1 justify-content-center align-items-center d-flex flex-column row-cols-sm-2 row-cols-md-5 pt-5 pb-5 ">
        <div class="col-lg-12 my-3 mb-5">
            <div class="row d-flex flex-row justify-content-center align-items-start">
                <div class="col-lg-3 my-3 d-flex flex-column align-items-center justify-content-center">
                    <span style="font-size: 1.5rem; color: {{ $footer_text_color }}">Navigate</span>
                    <ul class="d-flex flex-column justify-content-center align-items-center list-unstyled">
                        @foreach ($menus as $menu)
                            <li class="my-3 list-footer"><a href="{{ url('/' . strtolower($menu['name'])) }}"
                                    style="text-decoration: none; color: var(--footer-text-color)">{{ $menu['name'] }}</a>
                            </li>
                        @endforeach
                        <li class="mt-3 list-footer"><a href="{{ url('/about') }}"
                                style="text-decoration: none; color: var(--footer-text-color)">About</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 my-3 d-flex flex-column align-items-center justify-content-center" style="width: auto">
                    <span style="font-size: 1.5rem; color: {{ $footer_text_color }}">Contact</span>
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <ul>
                            <li class="my-4"><span class="material-symbols-outlined"
                                    style="color: var(--footer-text-color)">
                                    phone_enabled
                                </span> &nbsp &nbsp <span
                                    style="color: var(--footer-text-color)">{{ $phone_number }}</span></li>
                            <li class="my-4"><span class="material-symbols-outlined"
                                    style="color: var(--footer-text-color)">
                                    mail
                                </span> &nbsp &nbsp <span
                                    style="color: var(--footer-text-color)">{{ $email }}</span></li>
                            <li class="my-4"><span class="material-symbols-outlined"
                                    style="color: var(--footer-text-color)">
                                    location_on
                                </span> &nbsp <span
                                    style="color: var(--footer-text-color)">{{ $main_address[0]['province'] ?? 'NaN' }},
                                    &nbsp {{ $main_address[0]['country'] ?? '' }}</span></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 my-3 d-flex flex-column align-items-center justify-content-center">
                    <span style="font-size: 1.5rem; color: {{ $footer_text_color }}">Social Media</span>
                    <div class="mt-3">
                        @foreach ($social_medias as $social_media)
                            <ul class="d-flex flex-column justify-content-center align-items-center list-unstyled">
                                <li class="wrapper-sosmed-icon">
                                    <a href="{{ url($social_media['account_link']) }}" target="_blank">
                                        <div class="sosmed-button">
                                            <div class="sosmed-icon"><i
                                                    class="fa-brands fa-{{ $social_media['type'] == 'twitter' ? 'x-twitter' : $social_media['type'] }}"></i>
                                            </div>
                                            &nbsp
                                            <span>{{ $social_media['account_name'] }}</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 d-flex flex-column align-items-center justify-content-center">
            <a href="{{ url('/') }}"
                class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                <img class="logo" src="{{ asset('assets/logo/' . $logo) }}" alt="">
            </a>
            <span class="text-center" style="color: {{ $footer_text_color }}; width: 100%;">Copyright © 2024
                {{ $blog_name }}</span>
        </div>
    </footer>
</div>
@script
    <script>
        let text_color;
        let footer_color;
        Livewire.on('footer-text-color', (data) => {
            text_color = data.data;
            document.body.style.setProperty('--footer-text-color', text_color);
        })
        Livewire.on('footer-color', (data) => {
            footer_color = data.data;
            document.body.style.setProperty('--footer-color', footer_color);
        })
    </script>
@endscript
