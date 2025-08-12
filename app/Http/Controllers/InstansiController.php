<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InstansiController extends Controller
{
    /**
     * Display the instansi settings form
     */
    public function index()
    {
        $instansi = Instansi::getInstansi();
        return view('admin.instansi.index', compact('instansi'));
    }

    /**
     * Update the instansi information
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_instansi' => 'required|string|max:255',
            'nama_ketua' => 'required|string|max:255',
            'nama_pembina' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url|max:255',
            'jam_mulai_absensi' => 'required|date_format:H:i',
            'jam_akhir_absensi' => 'required|date_format:H:i|after:jam_mulai_absensi',
        ], [
            'nama_instansi.required' => 'Nama instansi harus diisi',
            'nama_ketua.required' => 'Nama ketua harus diisi',
            'nama_pembina.required' => 'Nama pembina harus diisi',
            'alamat.required' => 'Alamat harus diisi',
            'no_telp.required' => 'No telepon harus diisi',
            'logo.image' => 'File harus berupa gambar',
            'logo.max' => 'Ukuran logo maksimal 2MB',
            'website.url' => 'Format website tidak valid',
            'jam_mulai_absensi.required' => 'Jam mulai absensi harus diisi',
            'jam_mulai_absensi.date_format' => 'Format jam mulai absensi tidak valid (HH:MM)',
            'jam_akhir_absensi.required' => 'Jam akhir absensi harus diisi',
            'jam_akhir_absensi.date_format' => 'Format jam akhir absensi tidak valid (HH:MM)',
            'jam_akhir_absensi.after' => 'Jam akhir absensi harus lebih besar dari jam mulai absensi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $instansi = Instansi::getInstansi();
            $data = $request->except(['logo', '_token']);

            // Convert time format to include seconds for database storage
            $data['jam_mulai_absensi'] = $request->jam_mulai_absensi . ':00';
            $data['jam_akhir_absensi'] = $request->jam_akhir_absensi . ':00';

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($instansi->logo && Storage::disk('public')->exists('logos/' . $instansi->logo)) {
                    Storage::disk('public')->delete('logos/' . $instansi->logo);
                }

                // Store new logo
                $logoFile = $request->file('logo');
                $logoName = time() . '_' . $logoFile->getClientOriginalName();
                $logoFile->storeAs('logos', $logoName, 'public');
                $data['logo'] = $logoName;
            }

            // Update or create instansi record
            if ($instansi->exists) {
                $instansi->update($data);
            } else {
                Instansi::create($data);
            }

            return redirect()->back()->with('success', 'Pengaturan instansi berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the logo
     */
    public function removeLogo()
    {
        try {
            $instansi = Instansi::getInstansi();

            if ($instansi->logo && Storage::disk('public')->exists('logos/' . $instansi->logo)) {
                Storage::disk('public')->delete('logos/' . $instansi->logo);
            }

            $instansi->update(['logo' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Logo berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
