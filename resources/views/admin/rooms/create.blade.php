
@extends('layouts.app')

@section('title', 'Tambah Kamar - Kos Bu Peni')

@push('styles')
<style>
    .form-header {
        margin-bottom: 25px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #176b4d;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .back-link:hover {
        color: #10543c;
    }

    .form-title {
        font-size: 27px;
        font-weight: 800;
        color: #1d3028;
        letter-spacing: -.7px;
        margin-bottom: 5px;
    }

    .form-subtitle {
        font-size: 14px;
        color: #78877f;
        margin: 0;
    }

    .form-panel {
        background: #fff;
        border: 1px solid #e8eee9;
        border-radius: 17px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(24, 58, 39, .035);
    }

    .section-heading {
        font-size: 15px;
        font-weight: 800;
        color: #1d3028;
        margin-bottom: 5px;
    }

    .section-description {
        color: #78877f;
        font-size: 12px;
        margin-bottom: 20px;
    }

    .form-label {
        color: #34483d;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        min-height: 45px;
        font-size: 13px;
        border-color: #e2eae4;
    }

    textarea.form-control {
        min-height: auto;
    }

    .form-control::placeholder {
        color: #a7b1aa;
    }

    .form-divider {
        border-top: 1px solid #edf1ee;
        margin: 27px 0;
    }

    .required-mark {
        color: #dc5555;
    }

    .image-upload-box {
        border: 1.5px dashed #cbded3;
        background: #f8fbf9;
        border-radius: 13px;
        padding: 22px;
        text-align: center;
        transition: .2s ease;
    }

    .image-upload-box:hover {
        border-color: #176b4d;
        background: #f1f8f3;
    }

    .upload-icon {
        font-size: 34px;
        margin-bottom: 9px;
    }

    .upload-title {
        color: #34483d;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .upload-description {
        color: #78877f;
        font-size: 11px;
        margin-bottom: 14px;
    }

    .image-preview {
        display: none;
        margin-top: 18px;
    }

    .image-preview img {
        width: 100%;
        max-width: 280px;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #e2eae4;
    }

    .btn-save {
        background: #176b4d;
        color: #fff;
        border: 1px solid #176b4d;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .btn-save:hover {
        background: #10543c;
        color: #fff;
    }

    .btn-cancel {
        background: white;
        color: #66756c;
        border: 1px solid #dce5de;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .btn-cancel:hover {
        background: #f5f8f6;
        color: #34483d;
    }

    @media (max-width: 768px) {
        .form-panel {
            padding: 20px;
        }

        .form-title {
            font-size: 23px;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

<div class="form-header">

    <a href="{{ route('admin.rooms.index') }}" class="back-link">
        ← Kembali ke Manajemen Kamar
    </a>

    <h1 class="form-title">Tambah Kamar Baru</h1>

    <p class="form-subtitle">
        Lengkapi informasi kamar untuk menambahkannya ke daftar penyewaan.
    </p>

</div>

@if($errors->any())
    <div class="alert alert-danger mb-4">
        <strong>Data belum berhasil disimpan.</strong>

        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-panel">

    <form
        action="{{ route('admin.rooms.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <!-- INFORMASI KAMAR -->
        <div>
            <h5 class="section-heading">Informasi Kamar</h5>

            <p class="section-description">
                Masukkan identitas dan informasi dasar kamar.
            </p>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">
                        Nama Kamar <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Kamar Deluxe"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Nomor Kamar <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        name="room_number"
                        class="form-control"
                        value="{{ old('room_number') }}"
                        placeholder="Contoh: A01"
                        required
                    >
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi Kamar</label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="3"
                        placeholder="Jelaskan kondisi dan keunggulan kamar..."
                    >{{ old('description') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">Fasilitas Kamar</label>

                    <textarea
                        name="facilities"
                        class="form-control"
                        rows="3"
                        placeholder="Contoh: Kasur, lemari, meja belajar, kamar mandi dalam..."
                    >{{ old('facilities') }}</textarea>
                </div>

            </div>
        </div>

        <div class="form-divider"></div>

        <!-- HARGA DAN STATUS -->
        <div>
            <h5 class="section-heading">Harga dan Ketersediaan</h5>

            <p class="section-description">
                Atur harga sewa dan status ketersediaan kamar.
            </p>

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">
                        Harga Sewa per Bulan (Rp)
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        value="{{ old('price') }}"
                        min="0"
                        placeholder="Contoh: 750000"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Status Kamar <span class="required-mark">*</span>
                    </label>

                    <select name="status" class="form-select" required>
                        <option value="available"
                            {{ old('status', 'available') === 'available' ? 'selected' : '' }}>
                            Tersedia
                        </option>

                        <option value="full"
                            {{ old('status') === 'full' ? 'selected' : '' }}>
                            Penuh
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <div class="form-divider"></div>

        <!-- FOTO KAMAR -->
        <div>
            <h5 class="section-heading">Foto Kamar</h5>

            <p class="section-description">
                Tambahkan foto agar calon penyewa dapat melihat kondisi kamar.
            </p>

            <div class="image-upload-box">

                <div class="upload-icon">📷</div>

                <div class="upload-title">
                    Pilih Foto Kamar
                </div>

                <p class="upload-description">
                    Format JPG, JPEG, PNG, atau WEBP. Maksimal 3 MB.
                </p>

                <input
                    type="file"
                    name="image"
                    id="roomImage"
                    class="form-control"
                    accept="image/jpeg,image/png,image/webp"
                >

                <div class="image-preview" id="imagePreview">
                    <p class="upload-title">Preview Foto:</p>
                    <img src="" id="previewImage" alt="Preview foto kamar">
                </div>

            </div>
        </div>

        <div class="form-divider"></div>

        <!-- TOMBOL -->
        <div class="d-flex gap-2 form-actions">

            <button type="submit" class="btn btn-save">
                ✓ Simpan Kamar
            </button>

            <a
                href="{{ route('admin.rooms.index') }}"
                class="btn btn-cancel text-decoration-none"
            >
                Batal
            </a>

        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
    const roomImage = document.getElementById('roomImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');

    let currentImageUrl = null;

    roomImage.addEventListener('change', function () {
        const file = this.files[0];

        if (currentImageUrl) {
            URL.revokeObjectURL(currentImageUrl);
            currentImageUrl = null;
        }

        if (!file) {
            imagePreview.style.display = 'none';
            previewImage.src = '';
            return;
        }

        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar.');
            this.value = '';
            imagePreview.style.display = 'none';
            return;
        }

        if (file.size > 3 * 1024 * 1024) {
            alert('Ukuran foto maksimal 3 MB.');
            this.value = '';
            imagePreview.style.display = 'none';
            return;
        }

        currentImageUrl = URL.createObjectURL(file);
        previewImage.src = currentImageUrl;
        imagePreview.style.display = 'block';
    });
</script>
@endpush
