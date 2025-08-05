<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    public function dashboard()
    {
        Log::info('=== ADMIN DASHBOARD ACCESSED ===', [
            'user' => Auth::user() ? Auth::user()->username : 'NULL',
            'level' => Auth::user() ? Auth::user()->level : 'NULL',
            'session_id' => session()->getId(),
            'session_data' => session()->all(),
            'auth_check' => Auth::check(),
            'request_path' => request()->path(),
            'request_url' => request()->url()
        ]);

        if (!Auth::check()) {
            Log::error('USER NOT AUTHENTICATED IN DASHBOARD!');
            return redirect()->route('login');
        }

        return view('pages.admin.dashboard');
    }

    public function users()
    {
        return view('pages.admin.users');
    }

    public function absensi()
    {
        return view('pages.admin.absensi');
    }

    public function kegiatan()
    {
        return view('pages.admin.kegiatan');
    }

    public function laporan()
    {
        return view('pages.admin.laporan');
    }

    public function settings()
    {
        return view('pages.admin.settings');
    }
}
