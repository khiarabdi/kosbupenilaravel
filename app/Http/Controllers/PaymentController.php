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

    // Verifikasi pembayaran oleh admin
    public function verify(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:approved,rejected',
            ],
        ]);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini sudah pernah diverifikasi.');
        }

        DB::transaction(function () use ($payment, $validated) {
            $payment->update([
                'status' => $validated['status'],
            ]);

            $payment->booking()->update([
                'status' => $validated['status'] === 'approved'
                    ? 'approved'
                    : 'pending',
            ]);
        });

        $message = $validated['status'] === 'approved'
            ? 'Pembayaran berhasil disetujui dan booking dikonfirmasi.'
            : 'Pembayaran ditolak. Penyewa dapat mengirim ulang bukti pembayaran.';

        return back()->with('success', $message);
    }
}
