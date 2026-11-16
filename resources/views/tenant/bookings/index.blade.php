
@extends('layouts.app')

@section('title', 'Booking Saya - Kos Bu Peni')

@section('content')

<style>
    .booking-header {
        background: linear-gradient(120deg, #145c3b, #198754);
        padding: 28px;
        border-radius: 18px;
        color: white;
        margin-bottom: 25px;
    }

    .booking-panel {
        background: white;
        border: 1px solid #e8eee9;
        border-radius: 16px;
        padding: 24px;
    }

    .booking-item {
        border: 1px solid #e8eee9;
        border-radius: 13px;
        padding: 20px;
        margin-bottom: 15px;
    }

    .booking-code {
        color: #198754;
        font-weight: 700;
    }

    .booking-label {
        font-size: 12px;
        color: #84918a;
        margin-bottom: 5px;
    }

    .booking-value {
        font-size: 14px;
        font-weight: 600;
        color: #34483c;
    }

    .booking-empty {
        padding: 55px 20px;
        text-align: center;
        color: #84918a;
    }

    .booking-empty i {
        font-size: 45px;
        color: #b9c9be;
    }
</style>

<div class="booking-header">
    <h3 class="fw-bold mb-2">Booking Saya</h3>
    <p class="mb-0 opacity-75">
        Pantau seluruh informasi dan status pemesanan kamar Anda.
    </p>
</div>

<div class="booking-panel">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Riwayat Pemesanan</h5>
            <small class="text-muted">Daftar booking akun Anda.</small>
        </div>

        <a href="{{ route('tenant.dashboard') }}"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    @forelse($bookings as $booking)

        <div class="booking-item">

            <div class="d-flex justify-content-between flex-wrap gap-2 mb-3">
                <div>
                    <div class="booking-label">Kode Booking</div>
                    <div class="booking-code">
                        {{ $booking->booking_code }}
                    </div>
                </div>

                @php
                    $statusClass = match($booking->status) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        'completed' => 'primary',
                        default => 'secondary',
                    };
                @endphp

                <span class="badge bg-{{ $statusClass }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="booking-label">Nama Kamar</div>
                    <div class="booking-value">
                        {{ $booking->room->name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="booking-label">Nomor Kamar</div>
                    <div class="booking-value">
                        {{ $booking->room->room_number ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="booking-label">Tanggal Masuk</div>
                    <div class="booking-value">
                        {{ $booking->check_in->format('d M Y') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="booking-label">Durasi Sewa</div>
                    <div class="booking-value">
                        {{ $booking->duration_months }} Bulan
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="booking-label">Total Harga</div>
                    <div class="booking-value">
                        Rp {{ number_format((float) $booking->total_price, 0, ',', '.') }}
                    </div>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('tenant.bookings.show', $booking->id) }}"
                       class="btn btn-green btn-sm w-100">
                        Lihat Detail
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>

    @empty

        <div class="booking-empty">
            <i class="bi bi-calendar-x"></i>

            <h5 class="fw-bold mt-3">Belum Ada Booking</h5>

            <p class="mb-0">
                Anda belum memiliki riwayat pemesanan kamar.
            </p>
        </div>

    @endforelse

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>

</div>

@endsection
