<div class="container-fluid login-form position-relative z-2">
    <div class="row d-flex flex-column align-items-center justify-content-center">
        <div class="col-lg-12 card no-padding login-form shadow-sm rounded-3">
            <div class="row d-flex justify-content-center align-items-center flex-row no-padding">
                <div class="col-lg-6 d-flex align-items-center justify-content-center image">
                    <img class="login-image" src="{{ asset('logo/login.jpg') }}" alt="">
                </div>
                <div class="col-lg-6 form d-flex flex-column justify-content-center align-items-center">
                  @if(session('message'))
                  <div class="alert alert-danger mt-3" role="alert">
                   {{session('message')}}
                  </div>
                  @endif
                  @if(session('status'))
                  <div class="alert alert-danger mt-3" role="alert">
                   {{session('status')}}
                  </div>
                  @endif
                  <span class="login-title mb-5">Login</span>
                    <form class="mb-5" wire:submit="login">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" wire:model="email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                              @error('email') <span class="error text-danger">{{ $message }}</span> @enderror 
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" wire:model="password" class="form-control" id="exampleInputPassword1">
                            @error('password') <span class="error text-danger">{{ $message }}</span> @enderror 
                        </div>
                        <div class="mb-5 form-check">
                            <input type="checkbox" wire:model="remember_me" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Ingat Saya</label>
                        </div>
                        <button type="submit" wire:click="loginButton()" class="btn btn-primary" style="width: 100%">
                            <div wire:loading wire:target="loginButton()" class="spinner-border spinner-border-sm"
                            role="status">
                        </div>
                            Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
