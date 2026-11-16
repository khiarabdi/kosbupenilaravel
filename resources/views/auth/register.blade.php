
@extends('layouts.app')

@section('title', 'Registrasi - Kos Bu Peni')

@section('content')

<style>
    .register-wrapper {
        min-height: 75vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 45px 15px;
    }

    .register-card {
        width: 100%;
        max-width: 760px;
        background: #ffffff;
        border: 1px solid #e8eee9;
        border-radius: 22px;
        padding: 38px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, .06);
    }

    .register-icon {
        width: 62px;
        height: 62px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        border-radius: 18px;
        background: #e9f7ef;
        color: #198754;
        font-size: 27px;
    }

    .register-title {
        color: #173b2b;
        font-weight: 700;
        letter-spacing: -.5px;
    }

    .register-subtitle {
        color: #84918a;
        font-size: 14px;
    }

    .register-card .form-label {
        color: #34483c;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .register-card .form-control {
        min-height: 46px;
        border: 1px solid #dfe8e1;
        border-radius: 10px;
        padding: 11px 13px;
        font-size: 14px;
    }

    .register-card textarea.form-control {
        min-height: 105px;
        resize: vertical;
    }

    .register-card .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, .10);
    }

    .register-section-title {
        color: #198754;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .register-divider {
        border-top: 1px solid #edf1ee;
        margin: 25px 0;
    }

    .register-button {
        min-height: 49px;
        border-radius: 10px;
        font-weight: 600;
        margin-top: 5px;
    }

    .register-footer {
        color: #84918a;
        font-size: 14px;
    }

    .register-footer a {
        color: #198754;
        font-weight: 600;
        text-decoration: none;
    }

    .register-footer a:hover {
        text-decoration: underline;
    }

    @media (max-width: 576px) {
        .register-card {
            padding: 27px 20px;
        }
    }
</style>

<div class="register-wrapper">
    <div class="register-card">

        <div class="text-center mb-4">

            <div class="register-icon">
                <i class="bi bi-person-plus-fill"></i>
            </div>

            <h3 class="register-title mb-2">
                Buat Akun Penyewa
            </h3>

            <p class="register-subtitle mb-0">
                Lengkapi informasi berikut untuk mulai memesan kamar.
            </p>

        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 small">
                <div class="fw-semibold mb-2">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    Registrasi gagal. Periksa kembali data Anda.
                </div>

                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST">
            @csrf

            <div class="register-section-title">
                <i class="bi bi-person-lines-fill me-1"></i>
                Informasi Pribadi
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Masukkan nama lengkap"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Alamat Email</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="contoh@email.com"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor HP</label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        placeholder="Contoh: 081234567890"
                        value="{{ old('phone') }}"
                        autocomplete="tel"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">NIK (16 Digit)</label>

                    <input
                        type="text"
                        name="nik"
                        class="form-control"
                        placeholder="Masukkan NIK"
                        maxlength="16"
                        inputmode="numeric"
                        value="{{ old('nik') }}"
                    >

                    <small class="text-muted">
                        NIK bersifat opsional.
                    </small>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Alamat Lengkap</label>

                    <textarea
                        name="address"
                        class="form-control"
                        placeholder="Masukkan alamat tempat tinggal Anda"
                        autocomplete="street-address"
                    >{{ old('address') }}</textarea>
                </div>

            </div>

            <div class="register-divider"></div>

            <div class="register-section-title">
                <i class="bi bi-shield-lock me-1"></i>
                Keamanan Akun
            </div>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password</label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                        required
                    >
                </div>

            </div>

            <button type="submit" class="btn btn-green w-100 register-button">
                Buat Akun Sekarang
                <i class="bi bi-arrow-right ms-1"></i>
            </button>

        </form>

        <div class="register-divider"></div>

        <div class="register-footer text-center">
            Sudah punya akun?
            <a href="{{ route('login') }}">Login sekarang</a>
        </div>

    </div>
</div>

@endsection
