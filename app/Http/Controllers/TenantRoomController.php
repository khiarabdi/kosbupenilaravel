<?php

namespace App\Http\Controllers;

use App\Models\Room;

class TenantRoomController extends Controller
{
    // Menampilkan daftar kamar yang tersedia
    public function index()
    {
        $rooms = Room::where('status', 'available')
            ->latest()
            ->paginate(9);

        return view('tenant.rooms.index', compact('rooms'));
    }

    // Menampilkan detail kamar
    public function show($id)
    {
        $room = Room::where('status', 'available')
            ->findOrFail($id);

        return view('tenant.rooms.show', compact('room'));
    }
}
