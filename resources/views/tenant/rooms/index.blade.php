
@extends('layouts.app')

@section('title', 'Daftar Kamar - Kos Bu Peni')

@section('content')

<style>
    .room-heading {
        background: linear-gradient(120deg, #145c3b, #198754);
        color: white;
        padding: 28px;
        border-radius: 18px;
        margin-bottom: 28px;
    }

    .tenant-room-card {
        background: white;
        border: 1px solid #e8eee9;
        border-radius: 16px;
        overflow: hidden;
        height: 100%;
        transition: .2s ease;
    }

    .tenant-room-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,.07);
    }

    .tenant-room-image {
        width: 100%;
        height: 210px;
        object-fit: cover;
        background: #f0f3f1;
    }

    .room-card-body {
        padding: 20px;
    }

    .room-price {
        color: #198754;
        font-size: 19px;
        font-weight: 700;
    }

    .room-number {
        color: #75847b;
        font-size: 13px;
    }

    .room-facilities {
        color: #6c757d;
        font-size: 13px;
        min-height: 40px;
    }

    .room-empty {
        background: white;
        border: 1px dashed #cbd8cf;
        border-radius: 16px;
        padding: 50px 20px;
        text-align: center;
    }
</style>

<div class="room-heading">
    <h3 class="fw-bold mb-2">Jelajahi Kamar Kos</h3>
    <p class="mb-0 opacity-75">
        Temukan kamar yang sesuai dengan kebutuhan dan budget Anda.
    </p>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="fw-bold mb-1">Kamar Tersedia</h5>
        <p class="text-muted small mb-0">
            Pilih kamar untuk melihat informasi lebih lanjut.
        </p>
    </div>

    <a href="{{ route('tenant.dashboard') }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>
        Dashboard
    </a>
</div>

<div class="row g-4">

    @forelse ($rooms as $room)

        <div class="col-md-6 col-lg-4">
            <div class="tenant-room-card">

                @if ($room->image)
                    <img
                        src="{{ asset('storage/' . $room->image) }}"
                        alt="{{ $room->name }}"
                        class="tenant-room-image"
                    >
                @else
                    <div class="tenant-room-image d-flex align-items-center justify-content-center">
                        <div class="text-center text-muted">
                            <i class="bi bi-house-door fs-1"></i>
                            <div class="small">Foto belum tersedia</div>
                        </div>
                    </div>
                @endif

                <div class="room-card-body">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-1">{{ $room->name }}</h5>

                        <span class="badge bg-success">
                            Tersedia
                        </span>
                    </div>

                    <div class="room-number mb-3">
                        <i class="bi bi-door-closed me-1"></i>
                        Nomor kamar: {{ $room->room_number }}
                    </div>

                    <div class="room-facilities mb-3">
                        <i class="bi bi-stars me-1"></i>
                        {{ $room->facilities ?: 'Informasi fasilitas belum tersedia.' }}
                    </div>

                    <div class="room-price mb-3">
                        Rp{{ number_format($room->price, 0, ',', '.') }}
                        <span class="text-muted fw-normal fs-6">/ bulan</span>
                    </div>

                    <a href="{{ route('tenant.rooms.show', $room->id) }}"
                       class="btn btn-green w-100">
                        Lihat Detail
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                </div>
            </div>
        </div>

    @empty

        <div class="col-12">
            <div class="room-empty">
                <i class="bi bi-house-x text-muted" style="font-size: 48px;"></i>
                <h5 class="fw-bold mt-3">Belum Ada Kamar Tersedia</h5>
                <p class="text-muted mb-0">
                    Saat ini belum ada kamar yang dapat disewa.
                    Silakan cek kembali nanti.
                </p>
            </div>
        </div>

    @endforelse

</div>

@if ($rooms->hasPages())
    <div class="mt-4">
        {{ $rooms->links() }}
    </div>
@endif

@endsection
