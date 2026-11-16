
@extends('layouts.app')

@section('title', 'Detail Kamar - Kos Bu Peni')

@section('content')

<style>
    .detail-panel {
        background: white;
        border: 1px solid #e8eee9;
        border-radius: 18px;
        overflow: hidden;
    }

    .detail-image {
        width: 100%;
        max-height: 430px;
        object-fit: cover;
        background: #f0f3f1;
    }

    .detail-content {
        padding: 28px;
    }

    .detail-label {
        color: #829087;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #263c30;
        font-weight: 600;
    }

    .detail-price {
        color: #198754;
        font-size: 25px;
        font-weight: 700;
    }

    .detail-description {
        color: #64746a;
        line-height: 1.8;
    }
</style>

<!-- Tombol Kembali -->
<div class="mb-4">
    <a href="{{ route('tenant.rooms.index') }}"
       class="btn btn-outline-secondary btn-sm">

        <i class="bi bi-arrow-left me-1"></i>
        Kembali ke Daftar Kamar

    </a>
</div>

<div class="detail-panel">

    <!-- Foto Kamar -->
    @if ($room->image)

        <img
            src="{{ asset('storage/' . $room->image) }}"
            alt="{{ $room->name }}"
            class="detail-image"
        >

    @else

        <div class="detail-image d-flex align-items-center justify-content-center">
            <div class="text-center text-muted">
                <i class="bi bi-house-door" style="font-size: 70px;"></i>
                <p class="mb-0">Foto kamar belum tersedia</p>
            </div>
        </div>

    @endif

    <!-- Informasi Kamar -->
    <div class="detail-content">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">

            <div>
                <span class="badge bg-success mb-2">
                    Kamar Tersedia
                </span>

                <h2 class="fw-bold mb-1">
                    {{ $room->name }}
                </h2>

                <p class="text-muted mb-0">
                    <i class="bi bi-door-closed me-1"></i>
                    Nomor kamar: {{ $room->room_number }}
                </p>
            </div>

        </div>

        <!-- Harga -->
        <div class="detail-price mb-4">
            Rp{{ number_format($room->price, 0, ',', '.') }}
            <span class="text-muted fs-6 fw-normal">
                / bulan
            </span>
        </div>

        <hr>

        <!-- Detail Informasi -->
        <div class="row g-4 mt-1">

            <div class="col-md-6">
                <div class="detail-label">
                    Nama Kamar
                </div>

                <div class="detail-value">
                    {{ $room->name }}
                </div>
            </div>

            <div class="col-md-6">
                <div class="detail-label">
                    Nomor Kamar
                </div>

                <div class="detail-value">
                    {{ $room->room_number }}
                </div>
            </div>

            <div class="col-12">
                <div class="detail-label">
                    Deskripsi Kamar
                </div>

                <div class="detail-description">
                    {{ $room->description ?: 'Deskripsi kamar belum tersedia.' }}
                </div>
            </div>

            <div class="col-12">
                <div class="detail-label">
                    Fasilitas Kamar
                </div>

                <div class="detail-description">
                    {{ $room->facilities ?: 'Informasi fasilitas belum tersedia.' }}
                </div>
            </div>

        </div>

        <hr class="my-4">

        <!-- Tombol Booking -->
        <div class="mt-4">

            <a href="{{ route('tenant.bookings.create', $room->id) }}"
               class="btn btn-green btn-lg w-100">

                <i class="bi bi-calendar-check me-2"></i>
                Booking Kamar Ini

            </a>

        </div>

        <p class="text-muted small text-center mt-3 mb-0">
            Pengajuan booking akan menunggu konfirmasi dari pengelola kos.
        </p>

    </div>

</div>

@endsection
