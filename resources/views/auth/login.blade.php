
@extends('layouts.app')

@section('title', 'Login - Kos Bu Peni')

@section('content')

<style>
    .login-wrapper {
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 45px 15px;
    }

    .login-card {
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        border: 1px solid #e8eee9;
        border-radius: 22px;
        padding: 38px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, .06);
    }

    .login-icon {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border-radius: 18px;
        background: #e9f7ef;
        color: #198754;
        font-size: 29px;
    }

    .login-title {
        color: #173b2b;
        font-weight: 700;
        letter-spacing: -.5px;
    }

    .login-subtitle {
        color: #84918a;
        font-size: 14px;
    }

    .login-card .form-label {
        color: #34483c;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .login-card .form-control {
        min-height: 48px;
        border: 1px solid #dfe8e1;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 14px;
    }

    .login-card .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, .10);
    }

    .login-button {
        min-height: 49px;
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: .2px;
        margin-top: 8px;
    }

    .login-divider {
        border-top: 1px solid #edf1ee;
        margin: 25px 0 20px;
    }

    .login-footer {
        color: #84918a;
        font-size: 14px;
    }

    .login-footer a {
        color: #198754;
        font-weight: 600;
        text-decoration: none;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        .login-card {
            padding: 28px 22px;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        <div class="text-center mb-4">

            <div class="login-icon">
                <i class="bi bi-house-door-fill"></i>
            </div>

            <h3 class="login-title mb-2">
                Selamat Datang!
            </h3>

            <p class="login-subtitle mb-0">
                Silakan login untuk melanjutkan ke akun Kos Bu Peni.
            </p>

        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 small">
                <i class="bi bi-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Alamat Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Masukkan email Anda"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    autofocus
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div class="form-check mb-3">
                <input
                    type="checkbox"
                    name="remember"
                    value="1"
                    class="form-check-input"
                    id="remember"
                >

                <label class="form-check-label small text-muted" for="remember">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn btn-green w-100 login-button">
                Login ke Akun
                <i class="bi bi-arrow-right ms-1"></i>
            </button>

        </form>

        <div class="login-divider"></div>

        <div class="login-footer text-center">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>

    </div>
</div>

@endsection
