<?php
namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KegiatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kegiatan::with('user');

        if ($request->nama) {
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%' . $request->nama . '%')
            );
        }

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $kegiatan = $query->orderBy('tanggal', 'desc')->paginate(10);

        return view('admin.kegiatan.index', compact('kegiatan'));
    }

    public function create()
    {
        $users = User::where('role', 'mahasiswa')->get();
        return view('admin.kegiatan.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kegiatan' => 'required|string|max:255',
        ]);

        Kegiatan::create([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'hari' => Carbon::parse($request->tanggal)->translatedFormat('l'),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $users = User::where('role', 'mahasiswa')->get();
        return view('admin.kegiatan.edit', compact('kegiatan', 'users'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kegiatan' => 'required|string|max:255',
        ]);

        $kegiatan->update([
            'user_id' => $request->user_id,
            'tanggal' => $request->tanggal,
            'hari' => Carbon::parse($request->tanggal)->translatedFormat('l'),
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kegiatan' => $request->kegiatan,
        ]);

        return redirect()->route('admin.kegiatan.index')->with('success', 'Data kegiatan diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('admin.kegiatan.index')->with('success', 'Kegiatan berhasil dihapus.');
    }
}
