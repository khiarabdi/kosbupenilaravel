<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // Form pembayaran milik booking penyewa
    public function create(Request $request, $bookingId)
    {
        $booking = $request->user()
            ->bookings()
            ->with('room', 'payments')
            ->findOrFail($bookingId);

        if ($booking->status !== 'pending') {
            return redirect()
                ->route('tenant.bookings.show', $booking->id)
                ->with('error', 'Booking ini tidak memerlukan pembayaran baru.');
        }

        $latestPayment = $booking->payments()->latest()->first();

        if ($latestPayment && $latestPayment->status === 'pending') {
            return redirect()
                ->route('tenant.bookings.show', $booking->id)
                ->with('error', 'Pembayaran Anda sedang menunggu verifikasi admin.');
        }

        return view('tenant.payments.create', compact('booking'));
    }

    // Menyimpan pembayaran dan bukti transfer
    public function store(Request $request, $bookingId)
    {
        $booking = $request->user()
            ->bookings()
            ->findOrFail($bookingId);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini tidak dapat dibayar.');
        }

        if ($booking->payments()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Pembayaran sebelumnya masih menunggu verifikasi.');
        }

        if (!config('kos.bank_name') || !config('kos.bank_account') || !config('kos.bank_holder')) {
            return back()->with('error', 'Rekening pemilik kos belum dikonfigurasi. Pembayaran belum dapat dikirim.');
        }

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'string',
                'in:transfer_bank',
            ],
            'proof' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:3072',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'proof.required' => 'Bukti transfer wajib diunggah.',
            'proof.image' => 'File harus berupa gambar.',
            'proof.mimes' => 'Format bukti harus JPG, JPEG, PNG, atau WEBP.',
            'proof.max' => 'Ukuran bukti maksimal 3 MB.',
        ]);

        $proofPath = $request->file('proof')
            ->store('payments', 'public');

        try {
            $booking->payments()->create([
                'user_id' => $request->user()->id,
                'amount' => $booking->total_price,
                'payment_method' => $validated['payment_method'],
                'proof_path' => $proofPath,
                'payment_date' => now()->toDateString(),
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($proofPath);
            throw $e;
        }

        return redirect()
            ->route('tenant.bookings.show', $booking->id)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Silakan menunggu verifikasi admin.');
    }

    // Riwayat pembayaran penyewa
    public function tenantIndex(Request $request)
    {
        $payments = $request->user()
            ->payments()
            ->with('booking.room')
            ->latest()
            ->paginate(10);

        return view('tenant.payments.index', compact('payments'));
    }

    // Daftar pembayaran untuk admin
    public function adminIndex()
    {
        $payments = Payment::with(['user', 'booking.room'])
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    // Verifikasi pembayaran oleh admin (atomic & aman dari klik bersamaan)
    public function verify(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'rejection_reason' => ['required_if:status,rejected', 'nullable', 'string', 'max:1000'],
        ], [
            'rejection_reason.required_if' => 'Alasan penolakan wajib diisi jika pembayaran ditolak.',
        ]);

        $result = DB::transaction(function () use ($payment, $validated) {
            $booking = Booking::whereKey($payment->booking_id)->lockForUpdate()->firstOrFail();
            $room = $booking->room()->lockForUpdate()->firstOrFail();
            $currentPayment = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();

            if ($currentPayment->status !== 'pending' || $booking->status !== 'pending') {
                return ['error' => 'Pembayaran atau booking ini sudah diverifikasi.'];
            }

            if ($validated['status'] === 'approved') {
                // Mencegah dua booking berbeda memperoleh kamar yang sama.
                if ($room->status !== 'available' || $room->bookings()
                    ->where('status', 'approved')->where('id', '!=', $booking->id)->exists()) {
                    return ['error' => 'Kamar sudah penuh. Pembayaran tidak dapat disetujui.'];
                }

                $currentPayment->update(['status' => 'approved', 'rejection_reason' => null]);
                $booking->update(['status' => 'approved']);
                $room->update(['status' => 'full']);

                return ['success' => 'Pembayaran disetujui, booking dikonfirmasi, dan kamar otomatis penuh.'];
            } else {
                $currentPayment->update([
                    'status' => 'rejected',
                    'rejection_reason' => trim($validated['rejection_reason']),
                ]);
                $booking->update(['status' => 'pending']);
                // Penolakan tidak mengubah status kamar. Admin dapat menerima bukti baru.
                return ['success' => 'Pembayaran ditolak dan alasan telah dikirim ke penyewa. Status kamar tidak berubah.'];
            }
        });

        return back()->with(isset($result['error']) ? 'error' : 'success',
            $result['error'] ?? $result['success']);
    }
}
