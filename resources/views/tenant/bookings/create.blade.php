
@extends('layouts.app')

@section('title', 'Booking Kamar - Kos Bu Peni')

@section('content')

<style>
    .booking-panel {
        background: white;
        border: 1px solid #e8eee9;
        border-radius: 18px;
        padding: 28px;
    }

    .booking-heading {
        background: linear-gradient(120deg, #145c3b, #198754);
        color: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 24px;
    }

    .booking-room-info {
        background: #f0f8f3;
        border: 1px solid #dceee2;
        border-radius: 14px;
        padding: 20px;
    }

    .booking-price {
        color: #198754;
        font-size: 23px;
        font-weight: 700;
    }

    .form-label {
        font-weight: 600;
        color: #34483b;
    }
</style>

<div class="booking-heading">
    <h3 class="fw-bold mb-2">Formulir Booking Kamar</h3>
    <p class="mb-0 opacity-75">
        Lengkapi informasi berikut untuk mengajukan penyewaan kamar.
    </p>
</div>

<div class="booking-panel">

    <div class="booking-room-info mb-4">

        <span class="badge bg-success mb-2">Kamar Tersedia</span>

        <h5 class="fw-bold mb-1">{{ $room->name }}</h5>

        <p class="text-muted mb-2">
            Nomor kamar: {{ $room->room_number }}
        </p>

        <div class="booking-price">
            Rp{{ number_format($room->price, 0, ',', '.') }}
            <span class="text-muted fs-6 fw-normal">/ bulan</span>
        </div>

    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Periksa kembali formulir Anda:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tenant.bookings.store', $room->id) }}"
          method="POST">

        @csrf

        <div class="mb-3">
            <label for="check_in" class="form-label">
                Tanggal Check-in
            </label>

            <input
                type="date"
                name="check_in"
                id="check_in"
                class="form-control"
                min="{{ now()->toDateString() }}"
                value="{{ old('check_in') }}"
                required
            >

            @error('check_in')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="duration_months" class="form-label">
                Durasi Sewa
            </label>

            <select
                name="duration_months"
                id="duration_months"
                class="form-select"
                required
            >
                <option value="">Pilih durasi sewa</option>

                @for ($i = 1; $i <= 24; $i++)
                    <option value="{{ $i }}"
                        {{ old('duration_months') == $i ? 'selected' : '' }}>
                        {{ $i }} Bulan
                    </option>
                @endfor

            </select>

            @error('duration_months')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">
                Catatan Tambahan
                <span class="text-muted fw-normal">(Opsional)</span>
            </label>

            <textarea
                name="notes"
                id="notes"
                class="form-control"
                rows="3"
                placeholder="Tuliskan catatan jika ada..."
            >{{ old('notes') }}</textarea>

            @error('notes')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Total biaya akan dihitung berdasarkan harga kamar dikalikan durasi sewa.
            Pengajuan booking akan berstatus menunggu konfirmasi.
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a href="{{ route('tenant.rooms.show', $room->id) }}"
               class="btn btn-outline-secondary">
                Kembali
            </a>

            <button type="submit" class="btn btn-green">
                <i class="bi bi-calendar-check me-1"></i>
                Ajukan Booking
            </button>

        </div>

    </form>

</div>

@endsection
