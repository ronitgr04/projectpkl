<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MahasiswaKegiatanController extends Controller
{
    /**
     * Display a listing of kegiatan for the authenticated mahasiswa.
     */
    public function index()
    {
        $user = Auth::user();

        // Cari mahasiswa berdasarkan user_id (relasi yang benar)
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Ambil kegiatan mahasiswa dengan pagination
        $kegiatan = Kegiatan::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam', 'desc')
            ->paginate(10);

        return view('mahasiswa.kegiatan.index', compact('kegiatan', 'mahasiswa'));
    }

    /**
     * Show the form for creating a new kegiatan.
     */
    public function create()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        return view('mahasiswa.kegiatan.create');
    }

    /**
     * Store a newly created kegiatan in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Cari mahasiswa berdasarkan relasi yang benar
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date|before_or_equal:today',
            'jam' => 'required|string',
            'kegiatan' => 'required|string|min:10',
            'foto_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'tanggal.required' => 'Tanggal kegiatan wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.before_or_equal' => 'Tanggal kegiatan tidak boleh di masa depan',
            'jam.required' => 'Jam kegiatan wajib diisi',
            'kegiatan.required' => 'Deskripsi kegiatan wajib diisi',
            'kegiatan.min' => 'Deskripsi kegiatan minimal 10 karakter',
            'foto_dokumentasi.image' => 'File harus berupa gambar',
            'foto_dokumentasi.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto_dokumentasi.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Hitung hari dari tanggal
            $tanggal = Carbon::parse($request->tanggal);
            $hari = $tanggal->format('l'); // English day name

            $data = [
                'mahasiswa_id' => $mahasiswa->id,
                'hari' => $hari,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kegiatan' => $request->kegiatan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto_dokumentasi')) {
                $file = $request->file('foto_dokumentasi');
                $filename = 'kegiatan_' . $mahasiswa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kegiatan', $filename, 'public');
                $data['foto_dokumentasi'] = $path;
            }

            Kegiatan::create($data);

            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('success', 'Kegiatan berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified kegiatan.
     */
    public function show($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $kegiatan = Kegiatan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$kegiatan) {
            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('error', 'Kegiatan tidak ditemukan');
        }

        return view('mahasiswa.kegiatan.show', compact('kegiatan'));
    }

    /**
     * Show the form for editing the specified kegiatan.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $kegiatan = Kegiatan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$kegiatan) {
            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('error', 'Kegiatan tidak ditemukan');
        }

        return view('mahasiswa.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified kegiatan in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $kegiatan = Kegiatan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$kegiatan) {
            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('error', 'Kegiatan tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date|before_or_equal:today',
            'jam' => 'required|string',
            'kegiatan' => 'required|string|min:10',
            'foto_dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'tanggal.required' => 'Tanggal kegiatan wajib diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'tanggal.before_or_equal' => 'Tanggal kegiatan tidak boleh di masa depan',
            'jam.required' => 'Jam kegiatan wajib diisi',
            'kegiatan.required' => 'Deskripsi kegiatan wajib diisi',
            'kegiatan.min' => 'Deskripsi kegiatan minimal 10 karakter',
            'foto_dokumentasi.image' => 'File harus berupa gambar',
            'foto_dokumentasi.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'foto_dokumentasi.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Hitung hari dari tanggal
            $tanggal = Carbon::parse($request->tanggal);
            $hari = $tanggal->format('l'); // English day name

            $data = [
                'hari' => $hari,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kegiatan' => $request->kegiatan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto_dokumentasi')) {
                // Hapus foto lama jika ada
                if ($kegiatan->foto_dokumentasi && Storage::disk('public')->exists($kegiatan->foto_dokumentasi)) {
                    Storage::disk('public')->delete($kegiatan->foto_dokumentasi);
                }

                $file = $request->file('foto_dokumentasi');
                $filename = 'kegiatan_' . $mahasiswa->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('kegiatan', $filename, 'public');
                $data['foto_dokumentasi'] = $path;
            }

            $kegiatan->update($data);

            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('success', 'Kegiatan berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified kegiatan from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $kegiatan = Kegiatan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$kegiatan) {
            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('error', 'Kegiatan tidak ditemukan');
        }

        try {
            // Hapus foto jika ada
            if ($kegiatan->foto_dokumentasi && Storage::disk('public')->exists($kegiatan->foto_dokumentasi)) {
                Storage::disk('public')->delete($kegiatan->foto_dokumentasi);
            }

            $kegiatan->delete();

            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('success', 'Kegiatan berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.kegiatan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Export kegiatan to PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Validasi input untuk filter tanggal
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Query kegiatan dengan filter tanggal
        $query = Kegiatan::where('mahasiswa_id', $mahasiswa->id);

        if ($request->start_date) {
            $query->where('tanggal', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        $kegiatan = $query->orderBy('tanggal', 'asc')
                         ->orderBy('jam', 'asc')
                         ->get();

        if ($kegiatan->isEmpty()) {
            return back()->with('error', 'Tidak ada kegiatan untuk dicetak dalam periode tersebut');
        }

        // Data untuk PDF
        $data = [
            'mahasiswa' => $mahasiswa,
            'kegiatan' => $kegiatan,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'generated_at' => now(),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('mahasiswa.kegiatan.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        // Nama file
        $filename = 'laporan-kegiatan-pkl-' . $mahasiswa->nim . '-' . date('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show export form
     */
    public function showExportForm()
    {
        $user = Auth::user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan');
        }

        // Get date range of kegiatan
        $firstKegiatan = Kegiatan::where('mahasiswa_id', $mahasiswa->id)
                                ->orderBy('tanggal', 'asc')
                                ->first();

        $lastKegiatan = Kegiatan::where('mahasiswa_id', $mahasiswa->id)
                               ->orderBy('tanggal', 'desc')
                               ->first();

        return view('mahasiswa.kegiatan.export', compact('mahasiswa', 'firstKegiatan', 'lastKegiatan'));
    }
}
