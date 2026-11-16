
@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Kos Bu Peni')

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
        box-shadow: 0 4px 15px rgba(0,0,0,.03);
    }

    .payment-table th {
        color: #718078;
        font-size: 12px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .payment-table td {
        vertical-align: middle;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .payment-proof {
        width: 65px;
        height: 65px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e8eee9;
    }

    .btn-approve {
        background: #198754;
        color: white;
        border: none;
    }

    .btn-approve:hover {
        background: #146c43;
        color: white;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
        border: none;
    }

    .btn-reject:hover {
        background: #bb2d3b;
        color: white;
    }
</style>

<div class="payment-header">
    <h3 class="fw-bold mb-2">
        <i class="bi bi-wallet2 me-2"></i>
        Verifikasi Pembayaran
    </h3>
    <p class="mb-0 opacity-75">
        Periksa bukti transfer dan kelola pembayaran penyewa Kos Bu Peni.
    </p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="payment-panel">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h5 class="fw-bold mb-1">Daftar Pembayaran</h5>
            <p class="text-muted small mb-0">
                Seluruh transaksi pembayaran penyewa.
            </p>
        </div>

        <span class="badge bg-success">
            {{ $payments->total() }} Transaksi
        </span>
    </div>

    @if($payments->count() > 0)

        <div class="table-responsive">
            <table class="table payment-table align-middle">

                <thead>
                    <tr>
                        <th>Penyewa</th>
                        <th>Booking</th>
                        <th>Nominal</th>
                        <th>Tanggal</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($payments as $payment)

                        @php
                            $statusColor = match($payment->status) {
                                'approved' => 'success',
                                'pending' => 'warning',
                                'rejected' => 'danger',
                                default => 'secondary',
                            };
                        @endphp

                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $payment->user->name ?? 'Pengguna tidak ditemukan' }}
                                </div>
                                <small class="text-muted">
                                    {{ $payment->user->email ?? '-' }}
                                </small>
                            </td>

                            <td>
                                <div class="fw-semibold">
                                    {{ $payment->booking->booking_code ?? '-' }}
                                </div>
                                <small class="text-muted">
                                    {{ $payment->booking->room->name ?? 'Kamar tidak ditemukan' }}
                                </small>
                            </td>

                            <td class="fw-bold text-success">
                                Rp{{ number_format((float) $payment->amount, 0, ',', '.') }}
                            </td>

                            <td>
                                {{ $payment->payment_date?->format('d M Y') ?? '-' }}
                            </td>

                            <td>
                                @if($payment->proof_path)
                                    <a href="{{ asset('storage/' . $payment->proof_path) }}"
                                       target="_blank"
                                       title="Lihat bukti transfer">

                                        <img src="{{ asset('storage/' . $payment->proof_path) }}"
                                             alt="Bukti pembayaran"
                                             class="payment-proof">
                                    </a>
                                @else
                                    <span class="text-muted small">
                                        Tidak ada bukti
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>

                            <td>
                                @if($payment->status === 'pending')

                                    <div class="d-flex flex-column gap-2">

                                        <form action="{{ route('admin.payments.verify', $payment->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Setujui pembayaran ini?')">

                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                   name="status"
                                                   value="approved">

                                            <button type="submit"
                                                    class="btn btn-approve btn-sm w-100">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Setujui
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.payments.verify', $payment->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Tolak pembayaran ini?')">

                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden"
                                                   name="status"
                                                   value="rejected">

                                            <label for="reason-{{ $payment->id }}" class="small fw-semibold">Alasan Penolakan</label>
                                            <textarea id="reason-{{ $payment->id }}" name="rejection_reason"
                                                      class="form-control form-control-sm" rows="2"
                                                      maxlength="1000" required
                                                      placeholder="Contoh: bukti transfer tidak jelas"></textarea>
                                            <button type="submit"
                                                    class="btn btn-reject btn-sm w-100">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Tolak
                                            </button>
                                        </form>

                                    </div>

                                @else
                                    @if($payment->status === 'rejected' && $payment->rejection_reason)
                                        <div class="text-danger small mb-1"><strong>Alasan:</strong> {{ $payment->rejection_reason }}</div>
                                    @endif
                                    <span class="text-muted small">
                                        Sudah diverifikasi
                                    </span>
                                @endif
                            </td>
                        </tr>

                    @endforeach
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>

    @else

        <div class="text-center py-5">
            <i class="bi bi-receipt text-secondary"
               style="font-size: 55px;"></i>

            <h5 class="fw-bold mt-3">Belum Ada Pembayaran</h5>

            <p class="text-muted mb-0">
                Pembayaran yang dikirim penyewa akan muncul di sini.
            </p>
        </div>

    @endif

</div>

@endsection
