
@extends('layouts.app')

@section('title', 'Detail Booking - Kos Bu Peni')

@section('content')

<style>
    .detail-header {
        background: linear-gradient(120deg, #145c3b, #198754);
        padding: 28px;
        border-radius: 18px;
        color: white;
        margin-bottom: 25px;
    }

    .detail-panel {
        background: white;
        border: 1px solid #e8eee9;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .detail-label {
        color: #84918a;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #34483c;
        font-weight: 600;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .detail-item {
        padding: 14px 0;
        border-bottom: 1px solid #edf1ee;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .payment-item {
        border: 1px solid #e8eee9;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 12px;
    }

    .total-box {
        background: #eff8f1;
        border-radius: 12px;
        padding: 18px;
    }

    .btn-green {
        background: #198754;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 11px 20px;
        font-weight: 600;
    }

    .btn-green:hover {
        background: #146c43;
        color: white;
    }
</style>

@php
    $bookingStatus = match($booking->status) {
        'approved', 'confirmed' => 'success',
        'pending' => 'warning',
        'cancelled', 'rejected' => 'danger',
        'completed' => 'primary',
        default => 'secondary',
    };

    $latestPayment = $booking->payments->sortByDesc('created_at')->first();

    $canPay = $booking->status === 'pending'
        && (!$latestPayment || $latestPayment->status === 'rejected');
@endphp

<div class="detail-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-2">Detail Booking</h3>
            <p class="mb-0 opacity-75">
                Informasi lengkap pemesanan kamar Anda.
            </p>
        </div>

        <span class="badge bg-light text-dark fs-6">
            {{ $booking->booking_code }}
        </span>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="detail-panel">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Informasi Pemesanan</h5>

        <span class="badge bg-{{ $bookingStatus }}">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    <div class="row g-3">

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Kode Booking</div>
                <div class="detail-value">{{ $booking->booking_code }}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Tanggal Pemesanan</div>
                <div class="detail-value">
                    {{ $booking->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Nama Penyewa</div>
                <div class="detail-value">{{ $booking->user->name ?? '-' }}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Email Penyewa</div>
                <div class="detail-value">{{ $booking->user->email ?? '-' }}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Nama Kamar</div>
                <div class="detail-value">{{ $booking->room->name ?? '-' }}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Nomor Kamar</div>
                <div class="detail-value">{{ $booking->room->room_number ?? '-' }}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Tanggal Masuk</div>
                <div class="detail-value">
                    {{ $booking->check_in->format('d M Y') }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="detail-item">
                <div class="detail-label">Durasi Sewa</div>
                <div class="detail-value">
                    {{ $booking->duration_months }} Bulan
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="detail-item">
                <div class="detail-label">Catatan Pemesanan</div>
                <div class="detail-value">
                    {{ $booking->notes ?: 'Tidak ada catatan.' }}
                </div>
            </div>
        </div>

    </div>

    <div class="total-box mt-4">
        <div class="detail-label">Total Biaya Sewa</div>
        <h3 class="fw-bold text-success mb-0">
            Rp {{ number_format((float) $booking->total_price, 0, ',', '.') }}
        </h3>
    </div>

    @if($canPay)
        <div class="mt-4">
            <a href="{{ route('tenant.payments.create', $booking->id) }}"
               class="btn btn-green w-100">
                <i class="bi bi-wallet2 me-2"></i>
                {{ $latestPayment && $latestPayment->status === 'rejected'
                    ? 'Kirim Ulang Pembayaran'
                    : 'Bayar Sekarang' }}
            </a>
        </div>
    @elseif($latestPayment && $latestPayment->status === 'pending')
        <div class="alert alert-warning mt-4 mb-0">
            <i class="bi bi-clock-history me-2"></i>
            Bukti pembayaran sedang menunggu verifikasi admin.
        </div>
    @elseif($booking->status === 'approved')
        <div class="alert alert-success mt-4 mb-0">
            <i class="bi bi-check-circle me-2"></i>
            Booking dan pembayaran Anda telah disetujui.
        </div>
    @endif

</div>

<div class="detail-panel">

    <h5 class="fw-bold mb-4">
        <i class="bi bi-wallet2 me-2 text-success"></i>
        Riwayat Pembayaran
    </h5>

    @forelse($booking->payments as $payment)

        @php
            $paymentStatus = match($payment->status) {
                'approved', 'paid', 'success' => 'success',
                'pending' => 'warning',
                'failed', 'rejected' => 'danger',
                default => 'secondary',
            };
        @endphp

        <div class="payment-item">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                <div>
                    <div class="detail-label">Nominal Pembayaran</div>
                    <div class="detail-value fs-5">
                        Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                    </div>
                </div>

                <span class="badge bg-{{ $paymentStatus }}">
                    {{ ucfirst($payment->status) }}
                </span>

            </div>

            <div class="row mt-2 g-3">

                <div class="col-md-4">
                    <div class="detail-label">Metode Pembayaran</div>
                    <div class="detail-value">
                        {{ $payment->payment_method === 'transfer_bank'
                            ? 'Transfer Bank'
                            : ($payment->payment_method ?: '-') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Tanggal Pembayaran</div>
                    <div class="detail-value">
                        {{ $payment->payment_date?->format('d M Y') ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="detail-label">Catatan</div>
                    <div class="detail-value">
                        {{ $payment->notes ?: '-' }}
                    </div>
                </div>

                @if($payment->status === 'rejected')
                    <div class="col-12">
                        <div class="alert alert-danger mb-0">
                            <strong>Alasan pembayaran ditolak:</strong>
                            {{ $payment->rejection_reason ?: 'Silakan hubungi pemilik kos untuk informasi lebih lanjut.' }}
                        </div>
                    </div>
                @endif

                @if($payment->proof_path)
                    <div class="col-12">
                        <a href="{{ asset('storage/' . $payment->proof_path) }}"
                           target="_blank"
                           class="btn btn-sm btn-outline-success">
                            <i class="bi bi-image me-1"></i>
                            Lihat Bukti Transfer
                        </a>
                    </div>
                @endif

            </div>

        </div>

    @empty

        <div class="text-center py-4">
            <i class="bi bi-receipt text-secondary" style="font-size: 40px;"></i>
            <h6 class="fw-bold mt-3">Belum Ada Pembayaran</h6>
            <p class="text-muted mb-0">
                Riwayat pembayaran Anda akan muncul di sini.
            </p>
        </div>

    @endforelse

</div>

<div class="d-flex justify-content-between flex-wrap gap-2 mb-4">

    <a href="{{ route('tenant.bookings.index') }}"
       class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Kembali ke Booking Saya
    </a>

    <a href="{{ route('tenant.dashboard') }}"
       class="btn btn-green">
        Dashboard
        <i class="bi bi-house ms-1"></i>
    </a>

</div>

@endsection
