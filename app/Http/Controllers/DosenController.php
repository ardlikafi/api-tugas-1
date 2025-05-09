<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosen;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Dokumentasi MATKUL_API (UNESA)",
 *      description="Dokumentasi API untuk proyek MATKUL_API. Mencakup Dosen, Mahasiswa, dll. Dilindungi oleh JWT.",
 *      @OA\Contact(
 *          email="admin@unesa.ac.id"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Tag(
 *     name="Dosen",
 *     description="Operasi terkait Data Dosen"
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Server Lokal (http://127.0.0.1:8000)"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT",
 *      description="Masukkan token JWT dengan prefix Bearer (Contoh: 'Bearer token_anda_disini')"
 * )
 *
 * @OA\Schema(
 *      schema="Dosen",
 *      title="Dosen Model",
 *      description="Representasi data dosen",
 *      @OA\Property(property="id", type="integer", format="int64", description="ID unik dosen", example=1),
 *      @OA\Property(property="nama_dosen", type="string", description="Nama lengkap dosen", example="Dr. Budi Santoso, M.Kom."),
 *      @OA\Property(property="nidn", type="string", description="Nomor Induk Dosen Nasional", example="0012345678"),
 *      @OA\Property(property="alamat", type="string", description="Alamat tinggal dosen", example="Jl. Merdeka No. 10, Surabaya"),
 *      @OA\Property(property="program_studi", type="string", description="Program studi yang diampu", example="S1 Teknik Informatika"),
 *      @OA\Property(property="email", type="string", format="email", description="Alamat email dosen", example="budi.santoso@example.com"),
 *      @OA\Property(property="tanggal_lahir", type="string", format="date", description="Tanggal lahir dosen (YYYY-MM-DD)", example="1980-05-15"),
 *      @OA\Property(property="jenis_kelamin", type="string", enum={"L", "P"}, description="Jenis kelamin (L: Laki-laki, P: Perempuan)", example="L"),
 *      @OA\Property(property="status", type="string", enum={"Dosen Tetap", "Dosen Tidak Tetap"}, description="Status kepegawaian dosen", example="Dosen Tetap"),
 *      @OA\Property(property="bidang_keahlian", type="string", description="Bidang keahlian utama dosen", example="Rekayasa Perangkat Lunak, Kecerdasan Buatan"),
 *      @OA\Property(property="created_at", type="string", format="date-time", description="Waktu data dibuat"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", description="Waktu data terakhir diupdate")
 * )
 *
 * @OA\Schema(
 *      schema="DosenInput",
 *      title="Dosen Input Payload",
 *      description="Data yang dibutuhkan untuk membuat atau mengupdate dosen",
 *      required={"nama_dosen", "nidn", "alamat", "program_studi", "email", "tanggal_lahir", "jenis_kelamin", "status", "bidang_keahlian"},
 *      @OA\Property(property="nama_dosen", type="string", example="Dr. Siti Aminah, M.Sc."),
 *      @OA\Property(property="nidn", type="string", example="0098765432"),
 *      @OA\Property(property="alamat", type="string", example="Jl. Pahlawan No. 1, Sidoarjo"),
 *      @OA\Property(property="program_studi", type="string", example="S1 Sistem Informasi"),
 *      @OA\Property(property="email", type="string", format="email", example="siti.aminah@example.com"),
 *      @OA\Property(property="tanggal_lahir", type="string", format="date", example="1985-10-20"),
 *      @OA\Property(property="jenis_kelamin", type="string", enum={"L", "P"}, example="P"),
 *      @OA\Property(property="status", type="string", enum={"Dosen Tetap", "Dosen Tidak Tetap"}, example="Dosen Tetap"),
 *      @OA\Property(property="bidang_keahlian", type="string", example="Data Science, Basis Data")
 * )
 */
class DosenController extends Controller
{
    // Definisi @OA\Schema dipindahkan ke atas blok komentar class

    /**
     * @OA\Get(
     *      path="/api/dosen",
     *      operationId="getDosenList",
     *      tags={"Dosen"},
     *      summary="Ambil daftar semua dosen",
     *      description="Mengembalikan daftar semua data dosen. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Operasi berhasil",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Dosen")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid")
     * )
     */
    public function index()
    {
        $dosen = Dosen::all();
        return response()->json($dosen);
    }

    /**
     * @OA\Post(
     *      path="/api/dosen",
     *      operationId="storeDosen",
     *      tags={"Dosen"},
     *      summary="Buat data dosen baru",
     *      description="Menyimpan data dosen baru ke dalam database. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Payload data dosen",
     *          @OA\JsonContent(ref="#/components/schemas/DosenInput")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Dosen berhasil dibuat",
     *          @OA\JsonContent(ref="#/components/schemas/Dosen")
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
            'nama_dosen' => 'required|string|max:255',
            'nidn' => 'required|string|max:50|unique:dosen,nidn',
            'alamat' => 'required|string',
            'program_studi' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:dosen,email',
            'tanggal_lahir' => 'required|date_format:Y-m-d',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'required|in:Dosen Tetap,Dosen Tidak Tetap',
            'bidang_keahlian' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 400);
        }

        $dosen = Dosen::create($validator->validated());
        return response()->json($dosen, 201);
    }

    /**
     * @OA\Get(
     *      path="/api/dosen/{id}",
     *      operationId="getDosenById",
     *      tags={"Dosen"},
     *      summary="Ambil data dosen berdasarkan ID",
     *      description="Mengembalikan satu data dosen. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID Dosen",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operasi berhasil",
     *          @OA\JsonContent(ref="#/components/schemas/Dosen")
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid"),
     *      @OA\Response(response=404, description="Dosen tidak ditemukan")
     * )
     */
    public function show(string $id)
    {
        $dosen = Dosen::findOrFail($id);
        return response()->json($dosen);
    }

    /**
     * @OA\Put(
     *      path="/api/dosen/{id}",
     *      operationId="updateDosen",
     *      tags={"Dosen"},
     *      summary="Update data dosen yang ada",
     *      description="Memperbarui data dosen. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID Dosen",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64")
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Payload data dosen untuk update",
     *          @OA\JsonContent(ref="#/components/schemas/DosenInput")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Dosen berhasil diupdate",
     *          @OA\JsonContent(ref="#/components/schemas/Dosen")
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Input tidak valid / Validasi gagal",
     *           @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(property="errors", type="object")
     *          )
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid"),
     *      @OA\Response(response=404, description="Dosen tidak ditemukan")
     * )
     */
    public function update(Request $request, string $id)
    {
        $dosen = Dosen::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_dosen' => 'sometimes|required|string|max:255',
            'nidn' => 'sometimes|required|string|max:50|unique:dosen,nidn,' . $dosen->id,
            'alamat' => 'sometimes|required|string',
            'program_studi' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:255|unique:dosen,email,' . $dosen->id,
            'tanggal_lahir' => 'sometimes|required|date_format:Y-m-d',
            'jenis_kelamin' => 'sometimes|required|in:L,P',
            'status' => 'sometimes|required|in:Dosen Tetap,Dosen Tidak Tetap',
            'bidang_keahlian' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 400);
        }

        $dosen->update($validator->validated());
        return response()->json($dosen);
    }

    /**
     * @OA\Delete(
     *      path="/api/dosen/{id}",
     *      operationId="deleteDosen",
     *      tags={"Dosen"},
     *      summary="Hapus data dosen",
     *      description="Menghapus data dosen berdasarkan ID. Membutuhkan autentikasi JWT.",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="ID Dosen",
     *          required=true,
     *          in="path",
     *          @OA\Schema(type="integer", format="int64")
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Dosen berhasil dihapus (No Content)"
     *      ),
     *      @OA\Response(response=401, description="Unauthenticated / Token tidak valid"),
     *      @OA\Response(response=404, description="Dosen tidak ditemukan")
     * )
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
             return response()->json(['message' => 'Dosen tidak ditemukan'], 404);
        }
        $dosen->delete();
        return response()->json(null, 204);
    }
}