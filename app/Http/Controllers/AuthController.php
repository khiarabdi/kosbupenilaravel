<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'nik' => ['nullable', 'digits:16', 'unique:users,nik'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'nik' => $validated['nik'] ?? null,
            'address' => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'tenant',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang!');
    }

    // Dashboard berdasarkan role pengguna
    public function dashboard()
    {
        $user = Auth::user();

        // Dashboard pemilik kos
        if ($user->isAdmin()) {

            $totalRooms = DB::table('rooms')->count();

            $totalTenants = DB::table('users')
                ->where('role', 'tenant')
                ->count();

            $totalBookings = DB::table('bookings')->count();

            $totalPayments = DB::table('payments')->count();

            $totalPaymentAmount = DB::table('payments')
                ->sum('amount');

            // Jumlah pembayaran yang menunggu verifikasi
            $pendingPayments = DB::table('payments')
                ->where('status', 'pending')
                ->count();

            // 5 booking terbaru
            $recentBookings = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->select(
                    'bookings.booking_code',
                    'bookings.check_in',
                    'bookings.total_price',
                    'bookings.status',
                    'bookings.created_at',
                    'users.name as tenant_name',
                    'rooms.name as room_name'
                )
                ->orderByDesc('bookings.created_at')
                ->limit(5)
                ->get();

            return view('dashboard.admin', compact(
                'totalRooms',
                'totalTenants',
                'totalBookings',
                'totalPayments',
                'totalPaymentAmount',
                'pendingPayments',
                'recentBookings'
            ));
        }

        // Dashboard penyewa
        return view('dashboard.tenant');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}
