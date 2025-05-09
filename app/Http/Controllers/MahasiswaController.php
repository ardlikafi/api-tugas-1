<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

/**
 * @OA\Tag(
 *     name="Mahasiswa",
 *     description="Operasi terkait Data Mahasiswa"
 * )
 *
 * @OA\Schema(
 *      schema="Mahasiswa",
 *      title="Mahasiswa Model",
 *      description="Representasi data mahasiswa",
 *      required={"id", "nim", "nama"},
 *      @OA\Property(property="id", type="integer", format="int64", description="ID unik internal", example=1),
 *      @OA\Property(property="nim", type="string", description="Nomor Induk Mahasiswa (digunakan sebagai ID di route)", example="18051204001"),
 *      @OA\Property(property="nama", type="string", description="Nama lengkap mahasiswa", example="Adi Santoso"),
 *      @OA\Property(property="jenis_kelamin", type="string", enum={"L", "P"}, description="Jenis kelamin (L/P)", example="L"),
 *      @OA\Property(property="alamat", type="string", description="Alamat tinggal mahasiswa", example="Jl. Pendidikan No. 5"),
 *      @OA\Property(property="tanggal_lahir", type="string", format="date", description="Tanggal lahir mahasiswa (YYYY-MM-DD)", example="2000-01-01"),
 *      @OA\Property(property="program_studi", type="string", description="Program studi", example="S1 Teknik Informatika"),
 *      @OA\Property(property="angkatan", type="integer", description="Tahun angkatan", example=2018),
 *      @OA\Property(property="email", type="string", format="email", description="Alamat email mahasiswa", example="adi.santoso@example.com"),
 *      @OA\Property(property="created_at", type="string", format="date-time", description="Waktu data dibuat"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", description="Waktu data terakhir diupdate")
 * )
 *
 * @OA\Schema(
 *      schema="MahasiswaInput",
 *      title="Mahasiswa Input Payload",
 *      description="Data yang dibutuhkan untuk membuat atau mengupdate mahasiswa",
 *      required={"nim", "nama", "jenis_kelamin", "alamat", "tanggal_lahir", "program_studi", "angkatan", "email"},
 *      @OA\Property(property="nim", type="string", example="19051204002"),
 *      @OA\Property(property="nama", type="string", example="Budi Raharjo"),
 *      @OA\Property(property="jenis_kelamin", type="string", enum={"L", "P"}, example="P"),
 *      @OA\Property(property="alamat", type="string", example="Jl. Teknologi No. 12"),
 *      @OA\Property(property="tanggal_lahir", type="string", format="date", example="2001-05-20"),
 *      @OA\Property(property="program_studi", type="string", example="S1 Sistem Informasi"),
 *      @OA\Property(property="angkatan", type="integer", example=2019),
 *      @OA\Property(property="email", type="string", format="email", example="budi.raharjo@example.com")
 * )
 */
class MahasiswaController extends Controller
{
    // Anotasi global (@OA\Info, @OA\Server, @OA\SecurityScheme) sebaiknya ada di SATU file saja.
    // Jika Anda sudah menambahkannya di DosenController, jangan tambahkan di sini lagi.
    // Pastikan @OA\SecurityScheme(securityScheme="bearerAuth", ...) sudah ada di salah satu file yang discan.


    /**
     * @OA\Get(
     *      path="/api/mahasiswa",
     *      operationId="getMahasiswaList",
     *      tags={"Mahasiswa"},
     *      summary="Ambil daftar semua mahasiswa (dengan opsi filter nama)",
     *      description="Mengembalikan daftar semua data mahasiswa. Dapat difilter berdasarkan nama. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="nama",
     *          description="Filter berdasarkan nama mahasiswa (pencarian sebagian, case-insensitive)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operasi berhasil",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Mahasiswa")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid")
     * )
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
     * Method ini biasanya untuk render view, bukan API endpoint, jadi tidak perlu anotasi Swagger.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *      path="/api/mahasiswa",
     *      operationId="storeMahasiswa",
     *      tags={"Mahasiswa"},
     *      summary="Buat data mahasiswa baru",
     *      description="Menyimpan data mahasiswa baru ke dalam database. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Payload data mahasiswa untuk dibuat",
     *          @OA\JsonContent(ref="#/components/schemas/MahasiswaInput")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Mahasiswa berhasil dibuat",
     *          @OA\JsonContent(ref="#/components/schemas/Mahasiswa")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Input tidak valid / Validasi gagal",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|unique:mahasiswa',
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required',
            'tanggal_lahir' => 'required|date',
            'program_studi' => 'required',
            'angkatan' => 'required|integer',
            'email' => 'required|email|unique:mahasiswa',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 400);
        }

        $mahasiswa = Mahasiswa::create($validator->validated()); // Gunakan validated() setelah validasi
        return response()->json($mahasiswa, 201);
    }

    /**
     * @OA\Get(
     *      path="/api/mahasiswa/{nim}",
     *      operationId="getMahasiswaByNim",
     *      tags={"Mahasiswa"},
     *      summary="Ambil data mahasiswa berdasarkan NIM",
     *      description="Mengembalikan satu data mahasiswa berdasarkan NIM. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="nim",
     *          description="NIM Mahasiswa",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operasi berhasil",
     *          @OA\JsonContent(ref="#/components/schemas/Mahasiswa")
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid"),
     *      @OA\Response(response=404, description="Mahasiswa tidak ditemukan")
     * )
     */
    public function show($nim)
    {
        // find() by default mencari berdasarkan primary key model, yang biasanya 'id'.
        // Karena route Anda menggunakan {nim}, Anda harus mencarinya berdasarkan kolom 'nim'.
        $mahasiswa = Mahasiswa::where('nim', $nim)->first(); // Cari berdasarkan kolom nim

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        return response()->json($mahasiswa);
    }

    /**
     * Show the form for editing a specified resource.
     * Method ini biasanya untuk render view, bukan API endpoint, jadi tidak perlu anotasi Swagger.
     */
    public function edit($nim)
    {
        //
    }

    /**
     * @OA\Put(
     *      path="/api/mahasiswa/{nim}",
     *      operationId="updateMahasiswaByNim",
     *      tags={"Mahasiswa"},
     *      summary="Update data mahasiswa berdasarkan NIM",
     *      description="Memperbarui data mahasiswa berdasarkan NIM. Field yang tidak disertakan atau null di payload tidak akan diubah (karena validasi `sometimes`). Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="nim",
     *          description="NIM Mahasiswa",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Payload data mahasiswa untuk update (field opsional)",
     *          @OA\JsonContent(ref="#/components/schemas/MahasiswaInput")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Mahasiswa berhasil diupdate",
     *          @OA\JsonContent(ref="#/components/schemas/Mahasiswa")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Input tidak valid / Validasi gagal",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid"),
     *      @OA\Response(response=404, description="Mahasiswa tidak ditemukan")
     * )
     */
    public function update(Request $request, $nim)
    {
        // find() by default mencari berdasarkan primary key model, yang biasanya 'id'.
        // Karena route Anda menggunakan {nim}, Anda harus mencarinya berdasarkan kolom 'nim'.
         $mahasiswa = Mahasiswa::where('nim', $nim)->first(); // Cari berdasarkan kolom nim

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        // Menggunakan Validator::make untuk validasi yang lebih fleksibel
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'jenis_kelamin' => 'sometimes|required|in:L,P',
            'alamat' => 'sometimes|required|string',
            'tanggal_lahir' => 'sometimes|required|date_format:Y-m-d', // Sesuaikan format date jika perlu
            'program_studi' => 'sometimes|required|string|max:100',
            'angkatan' => 'sometimes|required|integer',
            // Untuk validasi unique email dan nim, kita perlu mengecualikan mahasiswa yang sedang diupdate.
            // Ini biasanya dilakukan berdasarkan primary key model (id), bukan nim.
            // Jika primary key Anda BUKAN 'id', sesuaikan '. $mahasiswa->id'
            'email' => 'sometimes|required|email|max:255|unique:mahasiswa,email,' . ($mahasiswa->id ?? 'NULL'), // Handle jika $mahasiswa null (meskipun find...first() sudah ditangani)
            'nim' => 'sometimes|required|string|max:15|unique:mahasiswa,nim,' . ($mahasiswa->id ?? 'NULL'), // Handle jika $mahasiswa null
        ]);

         if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 400);
        }


        $mahasiswa->update($validator->validated()); // Gunakan validated()
        return response()->json($mahasiswa);
    }

    /**
     * @OA\Delete(
     *      path="/api/mahasiswa/{nim}",
     *      operationId="deleteMahasiswaByNim",
     *      tags={"Mahasiswa"},
     *      summary="Hapus data mahasiswa berdasarkan NIM",
     *      description="Menghapus data mahasiswa berdasarkan NIM. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="nim",
     *          description="NIM Mahasiswa",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Mahasiswa berhasil dihapus",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Mahasiswa berhasil dihapus")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid"),
     *      @OA\Response(response=404, description="Mahasiswa tidak ditemukan")
     * )
     */
    public function destroy($nim)
    {
        // find() by default mencari berdasarkan primary key model, yang biasanya 'id'.
        // Karena route Anda menggunakan {nim}, Anda harus mencarinya berdasarkan kolom 'nim'.
        $mahasiswa = Mahasiswa::where('nim', $nim)->first(); // Cari berdasarkan kolom nim

        if (!$mahasiswa) {
            return response()->json(['message' => 'Mahasiswa tidak ditemukan'], 404);
        }

        $mahasiswa->delete();

        return response()->json(['message' => 'Mahasiswa berhasil dihapus']); // Respons 200 OK dengan pesan
    }
}