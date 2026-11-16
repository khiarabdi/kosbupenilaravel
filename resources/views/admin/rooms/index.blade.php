
@extends('layouts.app')

@section('title', 'Manajemen Kamar - Kos Bu Peni')

@push('styles')
<style>
    .rooms-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 28px;
    }

    .rooms-title {
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -0.8px;
        color: #1d3028;
        margin-bottom: 5px;
    }

    .rooms-subtitle {
        color: #78877f;
        font-size: 14px;
        margin: 0;
    }

    .btn-add-room {
        background: #176b4d;
        color: white;
        border: none;
        border-radius: 11px;
        padding: 11px 18px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
    }

    .btn-add-room:hover {
        background: #10543c;
        color: white;
        transform: translateY(-2px);
    }

    .room-panel {
        background: #ffffff;
        border: 1px solid #e8eee9;
        border-radius: 17px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(24, 58, 39, 0.035);
    }

    .room-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 22px;
    }

    .room-panel-title {
        font-size: 17px;
        font-weight: 800;
        color: #1d3028;
        margin: 0;
    }

    .room-count {
        background: #e9f5ef;
        color: #176b4d;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .room-table {
        min-width: 850px;
        margin-bottom: 0;
    }

    .room-table thead th {
        background: #f5f8f6;
        color: #78877f;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .7px;
        padding: 15px 13px;
        border: none;
        white-space: nowrap;
    }

    .room-table thead th:first-child {
        border-radius: 9px 0 0 9px;
    }

    .room-table thead th:last-child {
        border-radius: 0 9px 9px 0;
    }

    .room-table tbody td {
        padding: 17px 13px;
        font-size: 13px;
        color: #46574e;
        border-bottom: 1px solid #edf1ee;
        vertical-align: middle;
    }

    .room-table tbody tr:last-child td {
        border-bottom: none;
    }

    .room-table tbody tr:hover {
        background: #fbfdfb;
    }

    .room-image {
        width: 85px;
        height: 72px;
        object-fit: cover;
        border-radius: 11px;
        border: 1px solid #e8eee9;
    }

    .room-placeholder {
        width: 85px;
        height: 72px;
        background: #e9f5ef;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
    }

    .room-number {
        color: #176b4d;
        font-weight: 800;
    }

    .room-name {
        color: #1d3028;
        font-weight: 700;
    }

    .room-price {
        color: #1d3028;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-available {
        background: #dcfce7;
        color: #166534;
    }

    .status-full {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .room-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
    }

    .room-actions form {
        margin: 0;
    }

    .btn-room-action {
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        transition: .2s ease;
    }

    .btn-edit-room {
        background: #fff7df;
        color: #986500;
        border: 1px solid #f5df9e;
    }

    .btn-edit-room:hover {
        background: #ffedbd;
        color: #805500;
    }

    .btn-status-room {
        background: #e9f5ef;
        color: #176b4d;
        border: 1px solid #cce5d6;
    }

    .btn-status-room:hover {
        background: #d8eee1;
        color: #10543c;
    }

    .btn-delete-room {
        background: #fff0f0;
        color: #c03939;
        border: 1px solid #f4cccc;
    }

    .btn-delete-room:hover {
        background: #ffe0e0;
        color: #a32222;
    }

    .room-empty {
        padding: 55px 20px !important;
        text-align: center;
    }

    .room-empty-icon {
        font-size: 42px;
        margin-bottom: 12px;
    }

    .room-empty-title {
        font-size: 15px;
        font-weight: 800;
        color: #1d3028;
        margin-bottom: 5px;
    }

    .room-empty-text {
        font-size: 13px;
        color: #78877f;
        margin-bottom: 18px;
    }

    .room-pagination {
        margin-top: 22px;
    }

    .room-pagination nav {
        display: flex;
        justify-content: flex-end;
    }

    .room-pagination p {
        font-size: 12px;
        color: #78877f;
    }

    @media (max-width: 768px) {
        .rooms-title {
            font-size: 23px;
        }

        .room-panel {
            padding: 17px;
        }

        .rooms-header {
            align-items: flex-start;
        }

        .btn-add-room {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')

<div class="rooms-header">
    <div>
        <h1 class="rooms-title">Manajemen Kamar</h1>
        <p class="rooms-subtitle">
            Kelola informasi, harga sewa, dan ketersediaan kamar kos.
        </p>
    </div>

    <a href="{{ route('admin.rooms.create') }}" class="btn-add-room">
        + Tambah Kamar
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <strong>Periksa kembali data berikut:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="room-panel">

    <div class="room-panel-header">
        <div>
            <h5 class="room-panel-title">Daftar Kamar</h5>
            <small class="text-muted">Informasi seluruh kamar yang terdaftar.</small>
        </div>

        <span class="room-count">
            {{ $rooms->total() }} Kamar
        </span>
    </div>

    <div class="table-responsive">
        <table class="table room-table align-middle">

            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nomor</th>
                    <th>Nama Kamar</th>
                    <th>Harga Sewa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($rooms as $room)
                    <tr>
                        <td>
                            @if($room->image)
                                <img
                                    src="{{ asset('storage/' . $room->image) }}"
                                    class="room-image"
                                    alt="{{ $room->name }}"
                                >
                            @else
                                <div class="room-placeholder">🏠</div>
                            @endif
                        </td>

                        <td>
                            <span class="room-number">
                                {{ $room->room_number }}
                            </span>
                        </td>

                        <td>
                            <span class="room-name">
                                {{ $room->name }}
                            </span>
                        </td>

                        <td>
                            <span class="room-price">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </span>
                            <div class="text-muted small">/ bulan</div>
                        </td>

                        <td>
                            @if($room->status === 'available')
                                <span class="status-badge status-available">
                                    <span class="status-dot"></span>
                                    Tersedia
                                </span>
                            @else
                                <span class="status-badge status-full">
                                    <span class="status-dot"></span>
                                    Penuh
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="room-actions">

                                <a
                                    href="{{ route('admin.rooms.edit', $room) }}"
                                    class="btn btn-room-action btn-edit-room"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.rooms.status', $room) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="btn btn-room-action btn-status-room"
                                    >
                                        Ubah Status
                                    </button>
                                </form>

                                <form
                                    action="{{ route('admin.rooms.destroy', $room) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kamar ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-room-action btn-delete-room"
                                    >
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="room-empty">
                            <div class="room-empty-icon">🏠</div>

                            <div class="room-empty-title">
                                Belum Ada Kamar
                            </div>

                            <p class="room-empty-text">
                                Tambahkan kamar pertama untuk mulai mengelola penyewaan kos.
                            </p>

                            <a
                                href="{{ route('admin.rooms.create') }}"
                                class="btn-add-room d-inline-block"
                            >
                                + Tambah Kamar
                            </a>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    @if($rooms->hasPages())
        <div class="room-pagination">
            {{ $rooms->links() }}
        </div>
    @endif

</div>

@endsection
