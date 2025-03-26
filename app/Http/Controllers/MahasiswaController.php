<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nama = $request->query('nama');

        $query = Mahasiswa::query();

        if ($nama) {
            $query->where('nama', 'like', '%' . $nama . '%');
        }

        $mahasiswa = $query->get();

        return response()->json($mahasiswa);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:mahasiswa',
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'program_studi' => 'required',
            'angkatan' => 'required|integer',
            'email' => 'required|email|unique:mahasiswa',
        ]);

        $mahasiswa = Mahasiswa::create($request->all());

        return response()->json($mahasiswa, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($nim)
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        return response()->json($mahasiswa);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nim)
    {
        // Method ini tidak digunakan untuk API, jadi biarkan kosong atau hapus
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nim)
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $request->validate([
            'nama' => 'sometimes|required',
            'jenis_kelamin' => 'sometimes|required|in:L,P',
            'alamat' => 'sometimes|required',
            'tanggal_lahir' => 'sometimes|required|date',
            'program_studi' => 'sometimes|required',
            'angkatan' => 'sometimes|required|integer',
            'email' => 'sometimes|required|email|unique:mahasiswa,email,' . $nim . ',nim',
        ]);

        $mahasiswa->update($request->all());

        return response()->json($mahasiswa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nim)
    {
        $mahasiswa = Mahasiswa::find($nim);

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $mahasiswa->delete();

        return response()->json(['message' => 'Mahasiswa berhasil dihapus']);
    }
}