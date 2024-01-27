<div>
    <nav class="m-4 shadow-sm rounded main-navbar d-flex flex-row justify-content-between">
        <div>
            <ul class="d-flex gap-3 align-items-center justify-content-center" style="width: auto">
                <li>
                    <img class="logo" src="{{ asset('assets/logo/' . $logo) }}" alt="">
                </li>
                <li class="title">Admin Dashboard</li>
            </ul>
        </div>
        <div class="d-flex align-items-start justify-content-around">
            <ul class="d-flex align-items-center justify-content-around">
                <li><a  class="mx-5 {{$current_url == 'admin/dashboard' ? 'active' : ''}} " href="{{url('admin/dashboard')}}"><i class="fa-solid fa-house"></i></a></li>
                <li><a  class="mx-5 {{$current_url == 'admin/posts' ? 'active' : ''}} " href="{{url('admin/posts')}}">Postingan</a></li>
                <li><a  class="mx-5 {{$current_url == 'admin/settings' ? 'active' : ''}} " href="{{url('admin/settings')}}">Pengaturan</a></li>
                <li><a  class="mx-5 {{$current_url == 'admin/about' ? 'active' : ''}} " href="{{url('admin/about')}}">Tentang</a></li>
            </ul>
            <ul class="d-flex ms-5 dark-mode align-items-center justify-content-center" style="width: auto;">
                <div id="dark-mode-switch">
                    <li><span class="material-symbols-outlined sun">
                            light_mode
                        </span></li>
                    <li><span class="material-symbols-outlined moon">
                            dark_mode
                        </span></li>
                </div>
            </ul>
            <ul class="d-flex me-5 align-items-center justify-content-center" style="width: auto;">
                <li>
                    <a href="" wire:click.prevent="logout()">

                        <i id="logout-button" class="logout-button fa-solid fa-power-off mt-2" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Logout"></i></a></li>
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
