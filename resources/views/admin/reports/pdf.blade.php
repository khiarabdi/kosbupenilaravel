<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        h2, p {
            text-align: center;
        }

        h3 {
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #777;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #e5eee8;
        }

        .summary td {
            border: 1px solid #777;
        }
    </style>
</head>

<body>

    <h2>LAPORAN KOS BU PENI</h2>
    <p>Sistem Informasi Penyewaan Kos</p>

    <p>
        Periode:
        {{ $from ?? 'Semua tanggal' }}
        sampai
        {{ $to ?? 'Semua tanggal' }}
    </p>

    <h3>Ringkasan Data</h3>

    <table class="summary">
        <tr>
            <th>Total Kamar</th>
            <th>Total Penyewa</th>
            <th>Total Booking</th>
            <th>Total Transaksi Pembayaran</th>
            <th>Total Nominal Pembayaran Tercatat</th>
        </tr>

        <tr>
            <td>{{ $totalRooms }}</td>
            <td>{{ $totalTenants }}</td>
            <td>{{ $totalBookings }}</td>
            <td>{{ $totalPayments }}</td>
            <td>Rp {{ number_format($totalPaymentAmount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h3>Rekap Booking</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Penyewa</th>
                <th>Kamar</th>
                <th>Check-in</th>
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
                    <td>{{ $booking->room_name }} ({{ $booking->room_number }})</td>
                    <td>{{ $booking->check_in }}</td>
                    <td>{{ $booking->duration_months }} bulan</td>
                    <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($booking->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data booking.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Rekap Pembayaran</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Penyewa</th>
                <th>Kamar</th>
                <th>Tanggal</th>
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
                    <td>{{ $payment->room_name }} ({{ $payment->room_number }})</td>
                    <td>{{ $payment->payment_date ?? $payment->created_at }}</td>
                    <td>{{ $payment->payment_method ?? '-' }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($payment->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Rekap Status Pembayaran</h3>

    <table>
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
                    <td colspan="2">Belum ada data pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
