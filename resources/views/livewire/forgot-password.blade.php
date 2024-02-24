<div style="padding-top: 80px">
    <div class="container d-flex align-items-center justify-content-center" style="height: 100dvh">
        <div class="row forgot-password-form card d-flex flex-row rounded shadow-sm" style="width: 100%">
            <div class="col-lg-6 d-flex">
                <img class="forgot-password-image" src="{{ asset('assets/additional-image/forgot-password.jpg') }}"
                    alt="">
            </div>
            @if (!$change_password_state)
                <form wire:submit="verifyCode"
                    class="col-lg-6 my-3 d-flex flex-column justify-content-around align-items-center" action=""
                    style="transition: var(--tran-04)">
                    <div class="text-center">
                        <span class="forgot-password-title"><strong>Masukkan kode verifikasi (6 digit) yang dikirimkan
                                ke
                                email {{ $current_email }}</strong></span>
                    </div>
                    @if (session('message'))
                        <div class="alert alert-danger text-center mt-2" role="alert">
                            <span style="font-size: 0.8rem; color: black;">{{ session('message') }}</span>
                        </div>
                    @endif
                    <div class="my-3">
                        <input type="text" wire:model="verification_code" class="form-control"
                            id="exampleFormControlInput1" placeholder="Masukkan kode verifikasi" style="width: auto">
                    </div>
                    @if (!$button_state)
                        <span class="text-center" id="countdown">Batas waktu mengirim kode verifikasi lagi: <p
                                id="count"></p></span>
                    @endif
                    <button wire:click="setButtonState('{{ (bool) false }}')" type="button"
                        class="send-verification-code-button my-3 shadow-sm"
                        @if (!$button_state) disabled @endif>
                        <span wire:loading.remove wire:target="setButtonState('{{ (bool) false }}')"> Kirim
                            Ulang</span>
                        <div wire:loading wire:target="setButtonState('{{ (bool) false }}')"
                            class="spinner-send-email spinner-border spinner-border-sm" role="status"></div>
                    </button>
                    <button type="submit" class="forgot-password-button my-3 shadow-sm">
                        <span wire:loading.remove wire:target="verifyCode">Submit</span>
                        <div wire:loading wire:target="verifyCode"
                            class="spinner-verify spinner-border spinner-border-sm" role="status"></div>
                    </button>
                    <a class="my-3" href="{{url('/login-admin')}}">Sudah ingat password-nya? Coba Login kembali</a>
                </form>
            @else
                <form wire:submit="updatePassword"
                    class="col-lg-6 my-3 d-flex flex-column justify-content-around align-items-center"
                    style="transition: var(--tran-04)">
                    <span class="forgot-password-title my-3"><strong>Reset Password <i
                                class="fa-solid fa-key"></i></strong></span>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Masukkan Password Baru</label>
                        <input type="password" wire:model="new_password" class="form-control"
                            id="exampleInputPassword1">
                        @error('new_password')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" wire:model="new_password_confirmation" class="form-control"
                            id="exampleInputPassword1">
                        @error('new_password_confirmation')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="forgot-password-button my-3 shadow-sm">
                        <span wire:loading.remove wire:target="updatePassword">Submit</span>
                        <div wire:loading wire:target="updatePassword"
                            class="spinner-verify spinner-border spinner-border-sm" role="status"></div>
                    </button>
                    <a class="my-3" href="{{url('/login-admin')}}">Sudah ingat password-nya? Coba Login kembali</a>
                </form>
            @endif
        </div>
    </div>
    <script>
        document.addEventListener('livewire:init', () => {

            let x; // Variabel untuk menyimpan ID interval

            function startCountdown() {
                // Get today's date and time
                let now = new Date().getTime();

                // Set the date we're counting down to
                let twoMinutesLater = new Date(now + 2 * 60 * 1000);

                // Update the countdown every 1 second
                x = setInterval(function() {
                    // Get today's date and time
                    let now = new Date().getTime();

                    // Find the distance between now and the count down date
                    let distance = twoMinutesLater - now;

                    // Time calculations for days, hours, minutes, and seconds
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Output the result in an element with id="count"
                    document.getElementById("count").innerHTML = minutes + " menit " + seconds + " detik ";

                    // If the count down is over, write some text 
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("count").innerHTML = "Waktu Habis!";
                        Livewire.dispatch('enable-button', {
                            buttonState: true
                        });
                    }
                }, 1000);
            }

            // Memulai countdown saat halaman dimuat
            startCountdown();

            Livewire.on('disable-button', ({
                data
            }) => {
                if (!data) {
                    // Memulai kembali countdown jika tombol dinonaktifkan
                    startCountdown();
                }
            });

        })
    </script>
</div>
