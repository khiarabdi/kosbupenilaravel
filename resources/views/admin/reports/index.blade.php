
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kos Bu Peni</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f5f7f5;
            font-family: Arial, sans-serif;
            color: #26352b;
        }

        .header {
            background: #176b45;
            color: white;
            padding: 25px;
            border-radius: 12px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
            height: 100%;
        }

        .stat-card h3 {
            font-size: 25px;
            font-weight: bold;
            margin-top: 10px;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            margin-top: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
        }

        .table thead th {
            background: #eaf3ed;
            white-space: nowrap;
        }

        .btn-green {
            background: #176b45;
            color: white;
        }

        .btn-green:hover {
            background: #105637;
            color: white;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .stat-card, .section-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .table {
                font-size: 11px;
            }

            @page {
                size: landscape;
                margin: 12mm;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline-secondary">
            ← Kembali ke Dashboard
        </a>

        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-dark">
                Cetak Laporan
            </button>

            <a href="{{ route('admin.reports.pdf', request()->query()) }}"
               class="btn btn-green">
                Export PDF
            </a>
        </div>
    </div>

    <div class="header mb-4">
        <h2 class="fw-bold mb-1">Laporan Kos Bu Peni</h2>
        <p class="mb-0">
            Rekap data penyewaan kamar dan pembayaran
        </p>
    </div>

    <div class="section-card no-print">
        <h5 class="fw-bold mb-3">Filter Periode Laporan</h5>

        <form action="{{ route('admin.reports.index') }}"
              method="GET"
              class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Dari Tanggal</label>
                <input type="date"
                       name="from"
                       class="form-control"
                       value="{{ $from }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date"
                       name="to"
                       class="form-control"
                       value="{{ $to }}">
            </div>

            <div class="col-md-4 d-flex align-items-end gap-2">
                <button class="btn btn-green">
                    Terapkan Filter
                </button>

                <a href="{{ route('admin.reports.index') }}"
                   class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="row g-3 mt-2">

        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-muted">Total Kamar</div>
                <h3>{{ $totalRooms }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-muted">Total Penyewa</div>
                <h3>{{ $totalTenants }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-muted">Total Booking</div>
                <h3>{{ $totalBookings }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="text-muted">Total Pembayaran Tercatat</div>
                <h3 style="font-size: 21px">
                    Rp {{ number_format($totalPaymentAmount, 0, ',', '.') }}
                </h3>
                <small class="text-muted">
                    {{ $totalPayments }} transaksi
                </small>
            </div>
        </div>

    </div>

    <div class="section-card">
        <h5 class="fw-bold mb-3">Rekap Status Pembayaran</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Jumlah Transaksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($paymentStatus as $item)
                        <tr>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">
                                Belum ada data pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="section-card">
        <h5 class="fw-bold mb-3">Rekap Data Booking</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Nama Penyewa</th>
                        <th>Kamar</th>
                        <th>Tanggal Check-in</th>
                        <th>Durasi</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $booking->booking_code }}</td>
                            <td>{{ $booking->tenant_name }}</td>
                            <td>
                                {{ $booking->room_name }}
                                ({{ $booking->room_number }})
                            </td>
                            <td>{{ $booking->check_in }}</td>
                            <td>{{ $booking->duration_months }} bulan</td>
                            <td>
                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                            </td>
                            <td>{{ ucfirst($booking->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                Belum ada data booking.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="section-card">
        <h5 class="fw-bold mb-3">Rekap Data Pembayaran</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Booking</th>
                        <th>Nama Penyewa</th>
                        <th>Kamar</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Metode</th>
                        <th>Nominal</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->booking_code }}</td>
                            <td>{{ $payment->tenant_name }}</td>
                            <td>
                                {{ $payment->room_name }}
                                ({{ $payment->room_number }})
                            </td>
                            <td>
                                {{ $payment->payment_date ?? $payment->created_at }}
                            </td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                            <td>
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ ucfirst($payment->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                Belum ada data pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center text-muted mt-4">
        <small>
            Laporan Sistem Informasi Penyewaan Kos Bu Peni
        </small>
    </div>

</div>

</body>
</html>
