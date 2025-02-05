<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Jadwal::all();
        $tahuns = TahunAkademik::latest()->get();
        return view('pages.data-master.data-tahun-akademik', compact('tahuns','kelasAll'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'tahun_akademik' => [
                'required',
                'regex:/^[0-9]{4}\/[0-9]{4}$/'
            ],
            'status' => 'required|boolean',
        ], [
            'tahun_akademik.required' => 'Tahun akademik wajib diisi',
            'tahun_akademik.regex' => 'Format tahun akademik tidak valid [YYYY/YYYY]',
            'status.required' => 'Status wajib dipilih',
        ]);

        $tahun = explode('/', $request->tahun_akademik);
        $tahunPertama = (int) $tahun[0];
        $tahunKedua = (int) $tahun[1];

        if ($tahunKedua <= $tahunPertama) {
            return response()->json([
                'errors' => ['tahun_akademik' => ['Tahun kedua harus lebih besar dari tahun pertama']]
            ], 422);
        }

        if ($validateData['status'] == 1) {
            TahunAkademik::where('status', 1)->update(['status' => 0]);
        }

        $tahunAkademik = TahunAkademik::create([
            'tahun_akademik' => $request->tahun_akademik,
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Tahun akademik berhasil ditambahkan', 'tahun' => $tahunAkademik]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validateData = $request->validate([
            'tahun_akademik' => [
                'required',
                'regex:/^[0-9]{4}\/[0-9]{4}$/'
            ],
            'status' => 'required|boolean',
        ], [
            'tahun_akademik.required' => 'Tahun akademik wajib diisi.',
            'tahun_akademik.regex' => 'Format tahun akademik tidak valid [YYYY/YYYY]',
            'status.required' => 'Status wajib dipilih',
        ]);

        if ($validateData['status'] == 1) {
            TahunAkademik::where('status', 1)->update(['status' => 0]);
        }

        $tahunAkademik = TahunAkademik::findOrFail($id);
        $tahunAkademik->update([
            'tahun_akademik' => $validateData['tahun_akademik'],
            'status' => $validateData['status'],
        ]);

        return response()->json(['success' => 'Tahun akademik berhasil diubah']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hapus = TahunAkademik::findOrFail($id);

        $hapus->delete();
        return response()->json(['success', 'Tahun akademik berhasil dihapus']);
    }
}
