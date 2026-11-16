
@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - Kos Bu Peni')

@section('content')

<style>
    .payment-header {
        background: linear-gradient(120deg, #145c3b, #198754);
        color: white;
        padding: 28px;
        border-radius: 18px;
        margin-bottom: 25px;
    }

    .payment-panel {
        background: white;
        border: 1px solid #e8eee9;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,.03);
    }

    .payment-amount {
        font-size: 22px;
        font-weight: 800;
        color: #198754;
    }

    .payment-label {
        color: #84918a;
        font-size: 12px;
        margin-bottom: 5px;
    }
</style>

<div class="payment-header">
    <h3 class="fw-bold mb-2">
        <i class="bi bi-wallet2 me-2"></i>
        Riwayat Pembayaran
    </h3>
    <p class="mb-0 opacity-75">
        Pantau seluruh transaksi pembayaran Anda.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Daftar Transaksi</h5>

    <span class="badge bg-success">
        {{ $payments->total() }} Transaksi
    </span>
</div>

@if($payments->count() > 0)

    @foreach($payments as $payment)

        @php
            $statusColor = match($payment->status) {
                'approved' => 'success',
                'pending' => 'warning',
                'rejected' => 'danger',
                default => 'secondary',
            };
        @endphp

        <div class="payment-panel">

            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">

                <div>
                    <div class="payment-label">Nominal Pembayaran</div>

                    <div class="payment-amount">
                        Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                    </div>
                </div>

                <span class="badge bg-{{ $statusColor }}">
                    {{ ucfirst($payment->status) }}
                </span>

            </div>

            <hr>

            <div class="row g-3">

                <div class="col-md-4">
                    <div class="payment-label">Kode Booking</div>
                    <div class="fw-semibold">
                        {{ $payment->booking->booking_code ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="payment-label">Nama Kamar</div>
                    <div class="fw-semibold">
                        {{ $payment->booking->room->name ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="payment-label">Tanggal Pembayaran</div>
                    <div class="fw-semibold">
                        {{ $payment->payment_date?->format('d M Y') ?? '-' }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="payment-label">Metode Pembayaran</div>
                    <div class="fw-semibold">
                        {{ $payment->payment_method === 'transfer_bank'
                            ? 'Transfer Bank BCA'
                            : ($payment->payment_method ?: '-') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="payment-label">Tanggal Pengajuan</div>
                    <div class="fw-semibold">
                        {{ $payment->created_at->format('d M Y, H:i') }}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="payment-label">Catatan</div>
                    <div class="fw-semibold">
                        {{ $payment->notes ?: '-' }}
                    </div>
                </div>

            </div>

            @if($payment->status === 'rejected')
                <div class="alert alert-danger mt-3 mb-0">
                    <strong>Alasan penolakan:</strong>
                    {{ $payment->rejection_reason ?: 'Silakan hubungi pemilik kos untuk informasi lebih lanjut.' }}
                </div>
            @endif

            <div class="d-flex flex-wrap gap-2 mt-4">

                @if($payment->proof_path)
                    <a href="{{ asset('storage/' . $payment->proof_path) }}"
                       target="_blank"
                       class="btn btn-outline-success btn-sm">
                        <i class="bi bi-image me-1"></i>
                        Lihat Bukti Transfer
                    </a>
                @endif

                @if($payment->booking)
                    <a href="{{ route('tenant.bookings.show', $payment->booking->id) }}"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-receipt me-1"></i>
                        Detail Booking
                    </a>
                @endif

            </div>

        </div>

    @endforeach

    <div class="mt-4">
        {{ $payments->links() }}
    </div>

@else

    <div class="payment-panel text-center py-5">
        <i class="bi bi-wallet2 text-secondary"
           style="font-size: 55px;"></i>

        <h5 class="fw-bold mt-3">Belum Ada Riwayat Pembayaran</h5>

        <p class="text-muted">
            Transaksi pembayaran Anda akan muncul di sini.
        </p>

        <a href="{{ route('tenant.bookings.index') }}"
           class="btn btn-success">
            Lihat Booking Saya
        </a>
    </div>

@endif

@endsection
