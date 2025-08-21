<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswa = Mahasiswa::with('user')->paginate(10);
        return view('admin.mahasiswa', compact('mahasiswa'));
    }
    public function profilSaya()
{
    // Ambil user yang sedang login
    $user = Auth::user();

    // Cari data mahasiswa yang terhubung dengan user tersebut

    $mahasiswa = Mahasiswa::where('user_id', $user->id_user)->first();

    if (!$mahasiswa) {
        return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
    }

    return view('mahasiswa.profil', compact('mahasiswa'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.createMahasiswa');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'universitas' => 'required|string|max:255',
            'nim' => 'required|string|max:255|unique:tabel_mahasiswa,nim',
            'mulai_magang' => 'required|date',
            'akhir_magang' => 'required|date|after:mulai_magang',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['foto']);

        // Handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Create directory if not exists
            if (!Storage::exists('public/mahasiswa_photos')) {
                Storage::makeDirectory('public/mahasiswa_photos');
            }

            $file->storeAs('public/mahasiswa_photos', $filename);
            $data['foto'] = $filename;
        }

        Mahasiswa::create($data);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return view('admin.editMahasiswa', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'universitas' => 'required|string|max:255',
            'nim' => 'required|string|max:255|unique:tabel_mahasiswa,nim,' . $mahasiswa->id,
            'mulai_magang' => 'required|date',
            'akhir_magang' => 'required|date|after:mulai_magang',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->except(['foto']);

        // Handle file upload
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($mahasiswa->foto && Storage::exists('public/mahasiswa_photos/' . $mahasiswa->foto)) {
                Storage::delete('public/mahasiswa_photos/' . $mahasiswa->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Create directory if not exists
            if (!Storage::exists('public/mahasiswa_photos')) {
                Storage::makeDirectory('public/mahasiswa_photos');
            }

            $file->storeAs('public/mahasiswa_photos', $filename);
            $data['foto'] = $filename;
        }

        $mahasiswa->update($data);

        // Fixed: redirect to correct route
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        // Delete photo if exists
        if ($mahasiswa->foto && Storage::exists('public/mahasiswa_photos/' . $mahasiswa->foto)) {
            Storage::delete('public/mahasiswa_photos/' . $mahasiswa->foto);
        }

        if($mahasiswa->user()){
            $mahasiswa->user()->delete();
        }
        $mahasiswa->delete();
        

        // Fixed: redirect to correct route
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus!');
    }

    /**
     * Create user account for mahasiswa (NON-API VERSION)
     * Menggunakan form submit biasa, redirect dengan session flash
     */
    public function createAccount(Request $request, Mahasiswa $mahasiswa)
    {
        try {
            // Check if mahasiswa already has user account
            if ($mahasiswa->hasUserAccount()) {
                return redirect()->route('admin.mahasiswa.index')
                    ->with('error', 'Mahasiswa sudah memiliki akun pengguna.');
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:tabel_user,username',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'username.required' => 'Username wajib diisi.',
                'username.unique' => 'Username sudah digunakan.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.'
            ]);

            if ($validator->fails()) {
                return redirect()->route('admin.mahasiswa.index')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Gagal membuat akun. Periksa data yang dimasukkan.');
            }

            // Generate kode_pengguna
            $kode_pengguna = 'MHS-' . strtoupper(Str::random(6));

            // Ensure unique kode_pengguna
            while (User::where('kode_pengguna', $kode_pengguna)->exists()) {
                $kode_pengguna = 'MHS-' . strtoupper(Str::random(6));
            }

            // Create user account
            $user = User::create([
                'kode_pengguna' => $kode_pengguna,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'level' => 'User'
            ]);

            // Link mahasiswa with user
            $mahasiswa->update(['user_id' => $user->id_user]);

            // Success message with account details
            $successMessage = "Akun mahasiswa '{$mahasiswa->nama}' berhasil dibuat!\n";
            $successMessage .= "Username: {$user->username}\n";
            $successMessage .= "Kode Pengguna: {$user->kode_pengguna}\n";
            $successMessage .= "Silakan catat informasi ini untuk diberikan kepada mahasiswa.";

            return redirect()->route('admin.mahasiswa.index')
                ->with('success', $successMessage)
                ->with('new_account', [
                    'username' => $user->username,
                    'kode_pengguna' => $user->kode_pengguna,
                    'mahasiswa_nama' => $mahasiswa->nama
                ]);
        } catch (\Exception $e) {
            Log::error('Error creating mahasiswa account: ' . $e->getMessage());
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Terjadi kesalahan saat membuat akun: ' . $e->getMessage());
        }
    }

    /**
     * Show create account form (Optional - jika ingin halaman terpisah)
     */
    public function showCreateAccountForm(Mahasiswa $mahasiswa)
    {
        // Check if mahasiswa already has user account
        if ($mahasiswa->hasUserAccount()) {
            return redirect()->route('admin.mahasiswa.index')
                ->with('error', 'Mahasiswa sudah memiliki akun pengguna.');
        }

        return view('admin.create-account', compact('mahasiswa'));
    }

    /**
     * Get mahasiswa data for API (if still needed)
     */
    public function api()
    {
        $mahasiswa = Mahasiswa::all();
        return response()->json($mahasiswa);

    }
    
}
