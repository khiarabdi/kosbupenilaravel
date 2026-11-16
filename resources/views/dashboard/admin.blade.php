
@extends('layouts.app')

@section('title', 'Dashboard Pemilik - Kos Bu Peni')

@section('content')

<style>
    .dashboard-header {
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border: none;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,.04);
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #e5f2e9;
        color: #174d37;
        font-size: 22px;
        margin-bottom: 18px;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #174d37;
    }

    .dashboard-panel {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,.04);
    }

    .table thead th {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
        font-size: 14px;
    }

    .status-label {
        background: #e5f2e9;
        color: #174d37;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
    }

    .btn-report {
        background: #174d37;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-report:hover {
        background: #103b29;
        color: white;
        transform: translateY(-2px);
    }

    .btn-rooms {
        background: #e5f2e9;
        color: #174d37;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-rooms:hover {
        background: #d2e8d9;
        color: #174d37;
    }

    .btn-payment {
        background: #fff3cd;
        color: #856404;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-payment:hover {
        background: #ffe69c;
        color: #664d03;
        transform: translateY(-2px);
    }

    .payment-stat-card {
        border: 1px solid #f5d98b;
        background: #fffdf5;
    }

    .payment-stat-card .stat-icon {
        background: #fff0c2;
        color: #9a6700;
    }

    .payment-stat-card .stat-number {
        color: #9a6700;
    }

    .btn-payment-action {
        display: inline-block;
        margin-top: 14px;
        background: #174d37;
        color: white;
        padding: 9px 15px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .btn-payment-action:hover {
        background: #103b29;
        color: white;
    }
</style>

{{-- HEADER DASHBOARD --}}
<div class="dashboard-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>
            <h2 class="fw-bold mb-1">Dashboard Pemilik Kos</h2>

            <p class="text-muted mb-0">
                Selamat datang, {{ auth()->user()->name }}!
            </p>
        </div>

        <div class="text-muted">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>

    </div>

    {{-- MENU AKSI --}}
    <div class="d-flex flex-wrap gap-2 mt-4">

        <a href="{{ route('admin.rooms.index') }}" class="btn btn-rooms">
            🏠 Kelola Kamar
        </a>

        <a href="{{ route('admin.payments.index') }}" class="btn btn-payment">
            💳 Verifikasi Pembayaran

            @if($pendingPayments > 0)
                <span class="badge bg-danger ms-1">
                    {{ $pendingPayments }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.reports.index') }}" class="btn btn-report">
            📊 Laporan & Rekap Data
        </a>

    </div>

</div>


{{-- STATISTIK --}}
<div class="row g-3 mb-4">

    {{-- TOTAL KAMAR --}}
    <div class="col-md-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon">🏠</div>

            <p class="text-muted mb-1">Total Kamar</p>

            <div class="stat-number">
                {{ number_format($totalRooms, 0, ',', '.') }}
            </div>

            <small class="text-muted">
                Kamar terdaftar
            </small>

        </div>

    </div>


    {{-- TOTAL PENYEWA --}}
    <div class="col-md-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon">👥</div>

            <p class="text-muted mb-1">Total Penyewa</p>

            <div class="stat-number">
                {{ number_format($totalTenants, 0, ',', '.') }}
            </div>

            <small class="text-muted">
                Akun penyewa terdaftar
            </small>

        </div>

    </div>


    {{-- TOTAL BOOKING --}}
    <div class="col-md-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon">📋</div>

            <p class="text-muted mb-1">Total Booking</p>

            <div class="stat-number">
                {{ number_format($totalBookings, 0, ',', '.') }}
            </div>

            <small class="text-muted">
                Seluruh pemesanan
            </small>

        </div>

    </div>


    {{-- TOTAL PEMBAYARAN --}}
    <div class="col-md-6 col-xl-3">

        <div class="stat-card">

            <div class="stat-icon">💰</div>

            <p class="text-muted mb-1">Nominal Pembayaran</p>

            <div class="stat-number" style="font-size: 22px;">
                Rp {{ number_format($totalPaymentAmount, 0, ',', '.') }}
            </div>

            <small class="text-muted">
                {{ $totalPayments }} data pembayaran tercatat
            </small>

        </div>

    </div>


    {{-- PEMBAYARAN MENUNGGU VERIFIKASI --}}
    <div class="col-md-6 col-xl-3">

        <div class="stat-card payment-stat-card">

            <div class="stat-icon">⏳</div>

            <p class="text-muted mb-1">Menunggu Verifikasi</p>

            <div class="stat-number">
                {{ number_format($pendingPayments, 0, ',', '.') }}
            </div>

            <small class="text-muted">
                Pembayaran belum diperiksa
            </small>

            <div>
                <a href="{{ route('admin.payments.index') }}"
                   class="btn-payment-action">
                    Verifikasi Sekarang →
                </a>
            </div>

        </div>

    </div>

</div>


{{-- REKAP BOOKING --}}
<div class="dashboard-panel">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>

            <h5 class="fw-bold mb-1">
                Booking Terbaru
            </h5>

            <p class="text-muted mb-0">
                Lima pemesanan terakhir yang masuk.
            </p>

        </div>

        <span class="badge text-bg-success">
            {{ $totalBookings }} Booking
        </span>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle">

            <thead>

                <tr>
                    <th>Kode Booking</th>
                    <th>Nama Penyewa</th>
                    <th>Kamar</th>
                    <th>Tanggal Masuk</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>

            </thead>


            <tbody>

                @forelse($recentBookings as $booking)

                    <tr>

                        <td class="fw-semibold">
                            {{ $booking->booking_code }}
                        </td>

                        <td>
                            {{ $booking->tenant_name }}
                        </td>

                        <td>
                            {{ $booking->room_name }}
                        </td>

                        <td>
                            {{ $booking->check_in }}
                        </td>

                        <td>
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </td>

                        <td>
                            <span class="status-label">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center text-muted py-4">
                            Belum ada data booking.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- FOOTER --}}
<div class="mt-4 text-muted small">
    Data dashboard diambil langsung dari database Kos Bu Peni.
</div>

@endsection
