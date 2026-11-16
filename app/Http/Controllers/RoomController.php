<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    // Menampilkan semua kamar
    public function index()
    {
        $rooms = Room::latest()->paginate(10);

        return view('admin.rooms.index', compact('rooms'));
    }

    // Form tambah kamar
    public function create()
    {
        return view('admin.rooms.create');
    }

    // Menyimpan kamar baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'room_number' => ['required', 'string', 'max:255', 'unique:rooms,room_number'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['available', 'full'])],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('rooms', 'public');
        }

        Room::create($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    // Form edit kamar
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    // Memperbarui kamar
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms', 'room_number')->ignore($room->id),
            ],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['available', 'full'])],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
        ]);

        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }

            $validated['image'] = $request->file('image')
                ->store('rooms', 'public');
        }

        if ($validated['status'] === 'available' && $room->bookings()->where('status', 'approved')->exists()) {
            return back()->withInput()->with('error', 'Kamar dengan booking disetujui tidak boleh diubah menjadi tersedia.');
        }

        $room->update($validated);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Data kamar berhasil diperbarui.');
    }

    // Menghapus kamar
    public function destroy(Room $room)
    {
        // Jangan hapus kamar yang masih memiliki riwayat booking
        if ($room->bookings()->exists()) {
            return back()->with(
                'error',
                'Kamar tidak dapat dihapus karena sudah memiliki riwayat booking. Ubah statusnya menjadi penuh jika tidak digunakan.'
            );
        }

        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }

    // Mengubah status ketersediaan kamar
    public function toggleStatus(Room $room)
    {
        if ($room->status === 'full' && $room->bookings()->where('status', 'approved')->exists()) {
            return back()->with('error', 'Kamar dengan booking aktif tidak dapat dijadikan tersedia.');
        }

        $room->status = $room->status === 'available' 
            ? 'full'
            : 'available';

        $room->save();

        return back()->with(
            'success',
            'Status kamar berhasil diperbarui.'
        );
    }
}
