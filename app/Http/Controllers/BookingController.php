<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Menampilkan daftar booking milik penyewa
    public function index(Request $request)
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['room', 'payments'])
            ->latest()
            ->paginate(10);

        return view('tenant.bookings.index', compact('bookings'));
    }

    // Menampilkan detail booking
    public function show(Request $request, $id)
    {
        $booking = $request->user()
            ->bookings()
            ->with(['room', 'payments'])
            ->findOrFail($id);

        return view('tenant.bookings.show', compact('booking'));
    }

    // Menampilkan formulir booking
    public function create($roomId)
    {
        $room = Room::where('status', 'available')
            ->findOrFail($roomId);

        return view('tenant.bookings.create', compact('room'));
    }

    // Menyimpan booking baru
    public function store(Request $request, $roomId)
    {
        $room = Room::where('status', 'available')
            ->findOrFail($roomId);

        $validated = $request->validate([
            'check_in' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'duration_months' => [
                'required',
                'integer',
                'min:1',
                'max:24',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'check_in.required' => 'Tanggal check-in wajib diisi.',
            'check_in.after_or_equal' => 'Tanggal check-in tidak boleh sebelum hari ini.',
            'duration_months.required' => 'Durasi sewa wajib diisi.',
            'duration_months.min' => 'Durasi minimal 1 bulan.',
            'duration_months.max' => 'Durasi maksimal 24 bulan.',
        ]);

        $totalPrice = $room->price * $validated['duration_months'];

        $booking = $request->user()->bookings()->create([
            'room_id' => $room->id,
            'booking_code' => 'KB-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'check_in' => $validated['check_in'],
            'duration_months' => $validated['duration_months'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('tenant.bookings.show', $booking->id)
            ->with('success', 'Booking kamar berhasil dibuat. Silakan menunggu konfirmasi pengelola.');
    }
}
