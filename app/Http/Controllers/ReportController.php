<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    private function getReportData(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $validated['from'] ?? null;
        $to = $validated['to'] ?? null;

        // Rekap booking
        $bookingsQuery = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                'bookings.*',
                'users.name as tenant_name',
                'rooms.name as room_name',
                'rooms.room_number'
            );

        if ($from) {
            $bookingsQuery->whereDate('bookings.created_at', '>=', $from);
        }

        if ($to) {
            $bookingsQuery->whereDate('bookings.created_at', '<=', $to);
        }

        $bookings = $bookingsQuery
            ->orderByDesc('bookings.created_at')
            ->get();

        // Rekap pembayaran
        $paymentsQuery = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'payments.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                'payments.*',
                'users.name as tenant_name',
                'bookings.booking_code',
                'rooms.name as room_name',
                'rooms.room_number'
            );

        // Gunakan payment_date.
        // Jika kosong, gunakan tanggal created_at.
        if ($from) {
            $paymentsQuery->whereRaw(
                'COALESCE(payments.payment_date, DATE(payments.created_at)) >= ?',
                [$from]
            );
        }

        if ($to) {
            $paymentsQuery->whereRaw(
                'COALESCE(payments.payment_date, DATE(payments.created_at)) <= ?',
                [$to]
            );
        }

        $payments = $paymentsQuery
            ->orderByDesc('payments.created_at')
            ->get();

        // Rekap status pembayaran
        $paymentStatus = DB::table('payments')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->when($from, function ($query) use ($from) {
                $query->whereRaw(
                    'COALESCE(payment_date, DATE(created_at)) >= ?',
                    [$from]
                );
            })
            ->when($to, function ($query) use ($to) {
                $query->whereRaw(
                    'COALESCE(payment_date, DATE(created_at)) <= ?',
                    [$to]
                );
            })
            ->groupBy('status')
            ->get();

        return [
            'from' => $from,
            'to' => $to,

            'totalRooms' => DB::table('rooms')->count(),

            'totalTenants' => DB::table('users')
                ->where('role', 'tenant')
                ->count(),

            'totalBookings' => $bookings->count(),

            'totalPayments' => $payments->count(),

            'totalPaymentAmount' => $payments->sum('amount'),

            'bookings' => $bookings,
            'payments' => $payments,
            'paymentStatus' => $paymentStatus,
        ];
    }

    public function index(Request $request)
    {
        $data = $this->getReportData($request);

        return view('admin.reports.index', $data);
    }

    public function pdf(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-kos-bu-peni.pdf');
    }
}
