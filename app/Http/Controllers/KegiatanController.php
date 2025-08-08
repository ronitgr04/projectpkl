<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::with('mahasiswa')->orderBy('tanggal', 'desc')->orderBy('jam', 'desc');

        // Filter berdasarkan mahasiswa
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $month = $request->bulan;
            $year = $request->filled('tahun') ? $request->tahun : now()->year;
            $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                  ->orWhere('hari', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($mq) use ($search) {
                      $mq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $kegiatan = $query->paginate(10);
        $mahasiswa = Mahasiswa::orderBy('nama')->get();

        return view('admin.kegiatan.index', compact('kegiatan', 'mahasiswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mahasiswa = Mahasiswa::orderBy('nama')->get();
        return view('admin.kegiatan.create', compact('mahasiswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:tabel_mahasiswa,id',
            'hari' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'foto_dokumentasi' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
        ], [
            'mahasiswa_id.required' => 'Mahasiswa harus dipilih.',
            'mahasiswa_id.exists' => 'Mahasiswa tidak valid.',
            'hari.required' => 'Hari harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jam.required' => 'Jam harus diisi.',
            'kegiatan.required' => 'Kegiatan harus diisi.',
            'foto_dokumentasi.image' => 'File harus berupa gambar.',
            'foto_dokumentasi.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'foto_dokumentasi.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        try {
            $data = [
                'mahasiswa_id' => $request->mahasiswa_id,
                'hari' => $request->hari,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kegiatan' => $request->kegiatan,
            ];

            // Handle foto upload
            if ($request->hasFile('foto_dokumentasi')) {
                $foto = $request->file('foto_dokumentasi');
                $filename = time() . '_' . $foto->getClientOriginalName();
                $path = $foto->storeAs('kegiatan/dokumentasi', $filename, 'public');
                $data['foto_dokumentasi'] = $path;
            }

            Kegiatan::create($data);

            return redirect()->route('admin.kegiatan.index')
                           ->with('success', 'Kegiatan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat menambahkan kegiatan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load('mahasiswa');
        return view('admin.kegiatan.show', compact('kegiatan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        $mahasiswa = Mahasiswa::orderBy('nama')->get();
        return view('admin.kegiatan.edit', compact('kegiatan', 'mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:tabel_mahasiswa,id',
            'hari' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jam' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'foto_dokumentasi' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
            'remove_photo' => 'nullable|boolean',
        ], [
            'mahasiswa_id.required' => 'Mahasiswa harus dipilih.',
            'mahasiswa_id.exists' => 'Mahasiswa tidak valid.',
            'hari.required' => 'Hari harus diisi.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jam.required' => 'Jam harus diisi.',
            'kegiatan.required' => 'Kegiatan harus diisi.',
            'foto_dokumentasi.image' => 'File harus berupa gambar.',
            'foto_dokumentasi.mimes' => 'Format gambar harus JPEG, JPG, atau PNG.',
            'foto_dokumentasi.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        try {
            $data = [
                'mahasiswa_id' => $request->mahasiswa_id,
                'hari' => $request->hari,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kegiatan' => $request->kegiatan,
            ];

            // Handle foto removal
            if ($request->remove_photo == '1' && $kegiatan->foto_dokumentasi) {
                Storage::disk('public')->delete($kegiatan->foto_dokumentasi);
                $data['foto_dokumentasi'] = null;
            }

            // Handle foto upload (new or replacement)
            if ($request->hasFile('foto_dokumentasi')) {
                // Delete old photo if exists
                if ($kegiatan->foto_dokumentasi) {
                    Storage::disk('public')->delete($kegiatan->foto_dokumentasi);
                }

                $foto = $request->file('foto_dokumentasi');
                $filename = time() . '_' . $foto->getClientOriginalName();
                $path = $foto->storeAs('kegiatan/dokumentasi', $filename, 'public');
                $data['foto_dokumentasi'] = $path;
            }

            $kegiatan->update($data);

            return redirect()->route('admin.kegiatan.index')
                           ->with('success', 'Kegiatan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Terjadi kesalahan saat memperbarui kegiatan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        try {
            // Delete associated photo if exists
            if ($kegiatan->foto_dokumentasi) {
                Storage::disk('public')->delete($kegiatan->foto_dokumentasi);
            }

            $kegiatan->delete();

            return redirect()->route('admin.kegiatan.index')
                           ->with('success', 'Kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat menghapus kegiatan: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk DataTables atau AJAX
     */
    public function api(Request $request): JsonResponse
    {
        $query = Kegiatan::with('mahasiswa')->orderBy('tanggal', 'desc');

        // Filtering
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kegiatan', 'like', "%{$search}%")
                  ->orWhere('hari', 'like', "%{$search}%")
                  ->orWhereHas('mahasiswa', function ($mq) use ($search) {
                      $mq->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $kegiatan = $query->get();

        $data = $kegiatan->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'nama' => $item->mahasiswa->nama ?? '-',
                'hari' => $item->formatted_hari,
                'tanggal' => $item->formatted_tanggal,
                'jam' => $item->jam,
                'kegiatan' => $item->kegiatan,
                'foto' => $item->foto_dokumentasi ? 'Ada' : 'Tidak ada',
                'aksi' => [
                    'show' => route('admin.kegiatan.show', $item->id),
                    'edit' => route('admin.kegiatan.edit', $item->id),
                    'destroy' => route('admin.kegiatan.destroy', $item->id),
                ]
            ];
        });

        return response()->json([
            'data' => $data,
            'recordsTotal' => $kegiatan->count(),
            'recordsFiltered' => $kegiatan->count(),
        ]);
    }

    /**
     * Export kegiatan berdasarkan filter
     */
    public function export(Request $request)
    {
        $query = Kegiatan::with('mahasiswa')->orderBy('tanggal', 'desc');

        // Apply same filters as index
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $month = $request->bulan;
            $year = $request->filled('tahun') ? $request->tahun : now()->year;
            $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
        }

        $kegiatan = $query->get();

        return view('admin.kegiatan.export', compact('kegiatan'));
    }
}
