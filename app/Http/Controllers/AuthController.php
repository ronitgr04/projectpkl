<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnLevel();
        }
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan level user
            $redirectTo = $this->getRedirectPath();

            return redirect()->intended($redirectTo)->with('success', 'Selamat datang, ' . Auth::user()->username);
        }

        return back()->withErrors([
            'username' => 'Username atau password tidak valid.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Redirect berdasarkan level user setelah login
     */
    private function getRedirectPath()
    {
        $user = Auth::user();

        switch ($user->level) {
            case 'Admin':
                return route('admin.dashboard');
            case 'User':
                return route('mahasiswa.dashboard');
            default:
                return route('dashboard'); // fallback
        }
    }

    /**
     * Redirect berdasarkan level untuk user yang sudah login
     */
    private function redirectBasedOnLevel()
    {
        $user = Auth::user();

        switch ($user->level) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'User':
                return redirect()->route('mahasiswa.dashboard');
            default:
                return redirect()->route('dashboard'); // fallback
        }
    }

    /**
     * Menampilkan dashboard umum (fallback)
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Redirect ke dashboard yang sesuai berdasarkan level
        switch ($user->level) {
            case 'Admin':
                return redirect()->route('admin.dashboard');
            case 'User':
                return redirect()->route('mahasiswa.dashboard');
            default:
                return view('dashboard', compact('user'));
        }
    }

    /**
     * Menampilkan admin dashboard
     */
    public function adminDashboard()
    {
        $user = Auth::user();
        $totalUsers = User::count();
        $adminUsers = User::where('level', 'Admin')->count();
        $regularUsers = User::where('level', 'User')->count();
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.index', compact('user', 'totalUsers', 'adminUsers', 'regularUsers', 'recentUsers'));
    }

    /**
     * Menampilkan mahasiswa dashboard
     */
    public function mahasiswaDashboard()
    {
        $user = Auth::user();

        // Data khusus untuk mahasiswa bisa ditambahkan di sini
        // Contoh: absensi, kegiatan, dll

        return view('mahasiswa.dashboard', compact('user'));
    }

    /**
     * Menampilkan halaman register (opsional)
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle register request (opsional)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_pengguna' => 'required|string|max:255|unique:tabel_user,kode_pengguna',
            'username' => 'required|string|max:255|unique:tabel_user,username',
            'password' => 'required|string|min:6|confirmed',
            'level' => 'required|in:Admin,User',
        ], [
            'kode_pengguna.required' => 'Kode pengguna wajib diisi',
            'kode_pengguna.unique' => 'Kode pengguna sudah digunakan',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'level.required' => 'Level wajib dipilih',
            'level.in' => 'Level tidak valid',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'kode_pengguna' => $request->kode_pengguna,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);

        Auth::login($user);

        // Redirect berdasarkan level setelah register
        $redirectTo = $this->getRedirectPath();

        return redirect($redirectTo)->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->username);
    }
}
