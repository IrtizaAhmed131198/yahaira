@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="login-pg">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="login-form">
                        <img src="images/logo.png" class="img-fluid logo-login" alt="">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control"
                                            placeholder="demo@example.com" value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="password" name="password" class="form-control" required>
                                        <i class="fa-solid fa-eye"></i>
                                        @error('password')
                                            <span class="text-danger" style="font-size: 14px;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="remember">
                                        <label for="remember">
                                            <input type="checkbox" id="remember" name="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn login-btn">Log in</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="login-banner">
                        <img src="images/login-img.png" class="img-fluid" alt="">
                        <div class="login-banner-heading">
                            <h1>Now Say <span class="red"><b>Good Bye</b></span> to your <br>
                                <span class="red">Neighborhood</span> matchmakers
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
