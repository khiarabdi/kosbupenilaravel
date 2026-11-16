
@extends('layouts.app')

@section('title', 'Dashboard Penyewa - Kos Bu Peni')

@section('content')

<style>
    .tenant-welcome {
        background: linear-gradient(120deg, #145c3b, #198754);
        border-radius: 20px;
        padding: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .tenant-welcome::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
        right: -55px;
        top: -75px;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-label {
        font-size: 13px;
        opacity: .8;
        margin-bottom: 8px;
    }

    .welcome-title {
        font-size: 27px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .welcome-description {
        font-size: 14px;
        opacity: .85;
        max-width: 500px;
        margin-bottom: 0;
    }

    .tenant-section-title {
        color: #173b2b;
        font-weight: 700;
        font-size: 19px;
    }

    .tenant-menu-card {
        background: white;
        border: 1px solid #e8eee9;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        transition: .2s ease;
    }

    .tenant-menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,.05);
    }

    .tenant-menu-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 23px;
        margin-bottom: 18px;
    }

    .icon-booking {
        background: #e9f7ef;
        color: #198754;
    }

    .icon-room {
        background: #fff5df;
        color: #d69b24;
    }

    .icon-profile {
        background: #eaf1ff;
        color: #4776c7;
    }

    .tenant-menu-card h5 {
        color: #263c30;
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .tenant-menu-card p {
        color: #84918a;
        font-size: 13px;
        min-height: 40px;
        margin-bottom: 18px;
    }

    .tenant-info-card {
        background: #f0f8f3;
        border: 1px solid #dceee2;
        border-radius: 16px;
        padding: 22px;
    }

    .tenant-info-card h6 {
        color: #17613d;
        font-weight: 700;
    }

    .tenant-info-card p {
        color: #648071;
        font-size: 13px;
        margin-bottom: 0;
        line-height: 1.7;
    }

    @media (max-width: 576px) {
        .tenant-welcome {
            padding: 25px 22px;
        }

        .welcome-title {
            font-size: 23px;
        }
    }
</style>

<!-- Welcome -->
<div class="mb-4">
    <div class="tenant-welcome">
        <div class="welcome-content">

            <div class="welcome-label">
                DASHBOARD PENYEWA
            </div>

            <h2 class="welcome-title">
                Halo, {{ auth()->user()->name }}!
            </h2>

            <p class="welcome-description">
                Selamat datang di sistem informasi Kos Bu Peni.
                Kelola informasi penyewaan kamar Anda dengan mudah melalui dashboard ini.
            </p>

        </div>
    </div>
</div>

<!-- Menu Utama -->
<div class="mb-3">
    <h4 class="tenant-section-title mb-1">
        Menu Utama
    </h4>

    <p class="text-muted small">
        Akses layanan dan informasi akun penyewaan Anda.
    </p>
</div>

<div class="row g-3 mb-4">

    <!-- Booking Saya -->
    <div class="col-md-4">
        <div class="tenant-menu-card">

            <div class="tenant-menu-icon icon-booking">
                <i class="bi bi-calendar-check"></i>
            </div>

            <h5>Booking Saya</h5>

            <p>
                Lihat informasi dan status pemesanan kamar kos Anda.
            </p>

            <a href="{{ route('tenant.bookings.index') }}"
               class="btn btn-green w-100">
                <i class="bi bi-arrow-right-circle me-1"></i>
                Lihat Booking
            </a>

        </div>
    </div>

    <!-- Lihat Kamar -->
    <div class="col-md-4">
        <div class="tenant-menu-card">

            <div class="tenant-menu-icon icon-room">
                <i class="bi bi-house-door"></i>
            </div>

            <h5>Lihat Kamar</h5>

            <p>
                Jelajahi pilihan kamar, fasilitas, foto, dan harga sewa.
            </p>

            <a href="{{ route('tenant.rooms.index') }}"
               class="btn btn-green w-100">
                <i class="bi bi-house-door me-1"></i>
                Jelajahi Kamar
            </a>

        </div>
    </div>

    <!-- Profil Saya -->
    <div class="col-md-4">
        <div class="tenant-menu-card">

            <div class="tenant-menu-icon icon-profile">
                <i class="bi bi-person-circle"></i>
            </div>

            <h5>Profil Saya</h5>

            <p>
                Lihat dan kelola informasi akun serta data pribadi Anda.
            </p>

            <button class="btn btn-outline-secondary w-100" disabled>
                <i class="bi bi-tools me-1"></i>
                Sedang Disiapkan
            </button>

        </div>
    </div>

</div>

<!-- Informasi -->
<div class="tenant-info-card">

    <div class="d-flex align-items-start gap-3">

        <div class="fs-3 text-success">
            <i class="bi bi-info-circle-fill"></i>
        </div>

        <div>
            <h6 class="mb-2">
                Informasi untuk Penyewa
            </h6>

            <p>
                Selamat menggunakan layanan Kos Bu Peni.
                Pastikan data akun Anda sudah benar agar proses pemesanan
                dan administrasi penyewaan dapat berjalan dengan lancar.
                Jika membutuhkan bantuan, silakan hubungi pengelola kos.
            </p>
        </div>

    </div>

</div>

@endsection
