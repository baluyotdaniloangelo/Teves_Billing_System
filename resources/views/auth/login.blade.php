@extends('layouts.app')
@section('content')

<style>
	.bg-image-login {
		background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.4)), 
		url('{{ asset('background/background.jpg') }}');
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		min-height: 100vh;
	}
</style>
    <div class="container-fluid bg-image-login">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

			<div class="card mb-3 border-0 shadow"
				 style="background: rgb(201 215 196 / 24%); backdrop-filter: blur(3px); ">

				<div class="card-body text-dark">

					<div class="pt-4 pb-2">

						<div class="d-flex justify-content-center py-4">
						<div style="
							background: #fff;
							border-radius: 50%;
							width: 130px;
							height: 130px;
							display: flex;
							align-items: center;
							justify-content: center;
							box-shadow: 0 10px 20px rgba(0,0,0,0.2);
						">
							<img src="{{ asset('client_logo/logo-2.png') }}" 
								 alt="" 
								 style="width:100px;">
						</div>
			</div>

            <h5 class="card-title text-center pb-0 fs-6 text-light"
                style="font-weight:bold; padding:0;">
                Portal
            </h5>
        </div>

        @if(Session::has('success'))
            <div class="bg-success text-white shadow">
                {{ Session::get('success') }}
            </div>
        @endif

        @if(Session::has('fail'))
            <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
                {{ Session::get('fail') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form class="row g-3 needs-validation" action="{{ route('login-user') }}" method="POST">
            @csrf

            <div class="col-12">
             
				<div class="form-floating mb-3">
					<input class="form-control text-center bg-transparent text-white border-light" name="user_name" id="user_name" required="" autocomplete="off" placeholder="Username" value="{{ old('user_name') }}">
					<label for="user_name" class="text-light">Username</label>
					<span class="text-danger">@error('user_name') {{$message}} @enderror</span>
				</div>
				
            </div>

            <div class="col-12">
			
                <div class="form-floating mb-3">
				<input type="password" 
                       class="form-control text-center bg-transparent text-white border-light"
                       id="InputPassword" 
                       name="InputPassword"
                       placeholder="Password"
                       value="{{ old('InputPassword') }}">
				<label for="InputPassword" class="form-label text-light">Password</label>
                <span class="text-danger">@error('InputPassword') {{$message}} @enderror</span>
				</div>
				
            </div>

            <div class="col-12">
                <button class="btn btn-primary w-100" type="submit">Login</button>
            </div>

            <div class="col-12">
                <p class="small mb-0 text-center text-light">
                    <a href="{{ route('passwordreset') }}" class=" text-light">Reset Password</a>
                </p>
            </div>

        </form>

    </div>
</div>

         
            </div>
          </div>
        </div>

      </section>

    </div>

@endsection