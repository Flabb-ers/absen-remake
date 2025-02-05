<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasAll = Jadwal::all();
        $ganjil = Semester::where('status', 1)
            ->whereRaw('semester % 2 = 1')
            ->first();

        $genap = Semester::where('status', 1)
            ->whereRaw('semester % 2 = 0')
            ->first();

        $semesters = Semester::orderBy('semester', 'asc')->get();
        return view('pages.data-master.data-semester', compact('semesters', 'ganjil', 'genap','kelasAll'));
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'semester' => 'required|numeric|max:99'
        ], [
            "semester.required" => "Semester harus diisi",
            "semester.numeric" => "Semester harus berupa angka",
            "semester.max" => "Maksimal 2 digit"
        ]);

        $activeSemester = Semester::where('status', 1)->first();
        $newSemesterIsEven = $validatedData['semester'] % 2 == 0;
        $status = 0;

        if ($activeSemester) {
            $activeSemesterIsEven = $activeSemester->semester % 2 == 0;

            if ($newSemesterIsEven == $activeSemesterIsEven) {
                $activeSemester->update(['status' => 0]);
                $status = 1;
            }
        } else {
            $status = 1;
        }

        $semester = Semester::create([
            'semester' => $validatedData['semester'],
            'status' => $status
        ]);

        if (!Semester::where('status', 1)->exists()) {
            $activeSemester->update(['status' => 1]);
        }

        return response()->json([
            'success' => 'Semester berhasil ditambahkan',
            'semester' => $semester,
            'active_semester' => Semester::where('status', 1)->first()
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        Kelas::where('id_semester',$semester->id)->delete();
        $semester->delete();
        return response()->json(['success' => 'Semester berhasil dihapus!']);
    }


    public function gantiStatus(Request $request)
    {
        $request->validate([
            'semester' => 'required|in:genap,ganjil',
        ]);

        $semesterStatus = $request->semester;

        try {
            Semester::query()->update(['status' => 0]);
            $countGenap = Semester::whereRaw('semester % 2 = 0')->count();

            if ($semesterStatus == 'genap') {
                if ($countGenap > 0) {
                    Semester::whereRaw('semester % 2 = 0')->update(['status' => 1]);
                    return response()->json(['success' => 'Semester genap berhasil diaktifkan']);
                } else {
                    Semester::whereRaw('semester % 2 != 0')->update(['status' => 1]);
                    return response()->json([
                        'warning' => 'Tidak ada semester genap yang tersedia, semester ganjil telah diaktifkan sebagai gantinya.'
                    ], 200);
                }
            } else if ($semesterStatus == 'ganjil') {
                Semester::whereRaw('semester % 2 != 0')->update(['status' => 1]);
                return response()->json(['success' => 'Semester ganjil berhasil diaktifkan']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }
}
