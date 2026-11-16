
@extends('layouts.app')

@section('title', 'Pembayaran - Kos Bu Peni')

@section('content')

<style>
    .payment-wrapper {
        max-width: 850px;
        margin: 0 auto;
    }

    .payment-card {
        background: white;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 5px 25px rgba(0,0,0,.05);
        margin-bottom: 22px;
    }

    .payment-header {
        background: linear-gradient(120deg, #145c3b, #198754);
        color: white;
        border-radius: 18px;
        padding: 28px;
        margin-bottom: 24px;
    }

    .payment-label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    .payment-input {
        border-radius: 10px;
        padding: 12px;
    }

    .payment-total {
        font-size: 28px;
        font-weight: 800;
        color: #198754;
    }

    .btn-green {
        background: #198754;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
    }

    .btn-green:hover {
        background: #146c43;
        color: white;
    }
</style>

<div class="payment-wrapper">

    <div class="mb-4">
        <a href="{{ route('tenant.bookings.show', $booking->id) }}"
           class="text-decoration-none text-muted">
            <i class="bi bi-arrow-left me-2"></i>
            Kembali ke Detail Booking
        </a>
    </div>

    <div class="payment-header">
        <h3 class="fw-bold mb-2">
            <i class="bi bi-wallet2 me-2"></i>
            Pembayaran Booking
        </h3>
        <p class="mb-0 opacity-75">
            Silakan unggah bukti pembayaran untuk melanjutkan proses booking.
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

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali data pembayaran:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="payment-card">
        <h5 class="fw-bold mb-4">Informasi Booking</h5>

        <div class="row mb-3">
            <div class="col-5 text-muted">Kode Booking</div>
            <div class="col-7 fw-semibold">
                {{ $booking->booking_code }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-5 text-muted">Nama Kamar</div>
            <div class="col-7 fw-semibold">
                {{ $booking->room->name ?? '-' }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-5 text-muted">Tanggal Check-in</div>
            <div class="col-7 fw-semibold">
                {{ $booking->check_in->format('d F Y') }}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-5 text-muted">Durasi Sewa</div>
            <div class="col-7 fw-semibold">
                {{ $booking->duration_months }} Bulan
            </div>
        </div>

        <hr>

        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Total Pembayaran</span>
            <span class="payment-total">
                Rp{{ number_format($booking->total_price, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <div class="payment-card">
    <h5 class="fw-bold mb-4">Informasi Rekening Pemilik Kos</h5>

    <div class="alert alert-light border mb-3">
        <div class="fw-bold text-primary mb-2">
            <i class="bi bi-bank me-2"></i>
            BANK BCA
        </div>

        <h3 class="fw-bold mb-1">1234567890</h3>

        <div class="text-muted">Atas Nama: Peni</div>
    </div>

    <p class="text-muted small mb-0">
        Silakan transfer sesuai nominal pembayaran ke rekening di atas,
        kemudian unggah bukti transfer.
    </p>
</div>

    <div class="payment-card">
        <h5 class="fw-bold mb-4">Formulir Pembayaran</h5>

        <form action="{{ route('tenant.payments.store', $booking->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-4">
                <label class="payment-label">Metode Pembayaran</label>

                <select name="payment_method"
                        class="form-select payment-input"
                        required>

                    <option value="">Pilih metode pembayaran</option>

                    <option value="transfer_bank"
                        {{ old('payment_method') == 'transfer_bank' ? 'selected' : '' }}>
                        Transfer Bank
                    </option>

                </select>
            </div>

            <div class="mb-4">
                <label class="payment-label">Bukti Transfer</label>

                <input type="file"
                       name="proof"
                       class="form-control payment-input"
                       accept=".jpg,.jpeg,.png,.webp"
                       required>

                <small class="text-muted">
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 3 MB.
                </small>
            </div>

            <div class="mb-4">
                <label class="payment-label">
                    Catatan <span class="text-muted">(Opsional)</span>
                </label>

                <textarea name="notes"
                          class="form-control payment-input"
                          rows="3"
                          placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
            </div>

            <div class="alert alert-warning">
                <i class="bi bi-info-circle me-2"></i>
                Pastikan bukti transfer yang diunggah jelas dan sesuai dengan nominal pembayaran.
            </div>

            <button type="submit" class="btn btn-green w-100">
                <i class="bi bi-send me-2"></i>
                Kirim Bukti Pembayaran
            </button>

        </form>
    </div>

</div>

@endsection
