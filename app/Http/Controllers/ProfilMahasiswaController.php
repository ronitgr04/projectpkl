<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Mahasiswa;
use App\Models\ProfilMahasiswa;
use App\Http\Controllers\Controller;

class ProfilMahasiswaController extends Controller
{
    /**
     * Middleware untuk memastikan user suda

     * Menampilkan profil mahasiswa
     */
    public function index()
    {
        $user = Auth::user();

        // Cari data mahasiswa berdasarkan user_id
        $mahasiswa = Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan. Silakan hubungi admin.');
        }

        // Cari atau buat profil mahasiswa
        $profil = ProfilMahasiswa::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'mahasiswa_id' => $mahasiswa->id,
                'jurusan' => 'IT' // default dari data yang ada
            ]
        );

        return view('mahasiswa.profile.index', compact('mahasiswa', 'profil'));
    }

    /**
     * Menampilkan form edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $profil = ProfilMahasiswa::firstOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            ['mahasiswa_id' => $mahasiswa->id]
        );
 
        return view('mahasiswa.profile.edit', compact('mahasiswa', 'profil'));
    }

    /**
     * Update profil mahasiswa
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Validation rules
        $validator = Validator::make($request->all(), [
            'no_telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
            'jurusan' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date|before:today',
            'jenis_kelamin' => 'nullable|in:L,P',
            'nama_pembimbing' => 'nullable|string|max:100',
            'no_telp_pembimbing' => 'nullable|string|max:15',
            'email_pembimbing' => 'nullable|email|max:100',
            'deskripsi_diri' => 'nullable|string|max:1000',
            'skills' => 'nullable|string|max:500',
            'instagram' => 'nullable|string|max:100',
            'linkedin' => 'nullable|string|max:100',
            'github' => 'nullable|string|max:100',
        ], [
            'no_telp.max' => 'Nomor telepon maksimal 15 karakter',
            'alamat.max' => 'Alamat maksimal 500 karakter',
            'jurusan.max' => 'Jurusan maksimal 100 karakter',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.in' => 'Jenis kelamin harus L atau P',
            'nama_pembimbing.max' => 'Nama pembimbing maksimal 100 karakter',
            'no_telp_pembimbing.max' => 'Nomor telepon pembimbing maksimal 15 karakter',
            'email_pembimbing.email' => 'Format email pembimbing tidak valid',
            'deskripsi_diri.max' => 'Deskripsi diri maksimal 1000 karakter',
            'skills.max' => 'Skills maksimal 500 karakter',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Update atau buat profil
        $profil = ProfilMahasiswa::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            $request->only([
                'no_telp', 'alamat', 'jurusan', 'tanggal_lahir', 'jenis_kelamin',
                'nama_pembimbing', 'no_telp_pembimbing', 'email_pembimbing',
                'deskripsi_diri', 'skills', 'instagram', 'linkedin', 'github'
            ])
        );

        return redirect()->route('mahasiswa.profil.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * API endpoint untuk mendapatkan data profil (untuk AJAX)
     */
    public function getProfilData()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return response()->json(['error' => 'Data mahasiswa tidak ditemukan'], 404);
        }

        $profil = ProfilMahasiswa::where('mahasiswa_id', $mahasiswa->id)->first();

        return response()->json([
            'mahasiswa' => $mahasiswa,
            'profil' => $profil,
            'completion_percentage' => $profil ? $profil->getCompletionPercentage() : 0
        ]);
    }

    /**
     * Check profil completion status
     */
    public function checkCompletion()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id_user)->first();

        if (!$mahasiswa) {
            return response()->json(['complete' => false, 'percentage' => 0]);
        }

        $profil = ProfilMahasiswa::where('mahasiswa_id', $mahasiswa->id)->first();

        if (!$profil) {
            return response()->json(['complete' => false, 'percentage' => 0]);
        }

        return response()->json([
            'complete' => $profil->isComplete(),
            'percentage' => $profil->getCompletionPercentage()
        ]);
    }
}