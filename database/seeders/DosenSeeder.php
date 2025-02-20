<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("dosen")->insert([
            'nama_dosen' => 'Faris Abdi El Hakim, S.Kom., M.Tr.Kom.',
            'nidn'=> '0016089503',
            'alamat'=> 'Jl. Kusuma Bangsa gg.Beringin II, Lamongan',
            'program_studi'=> 'Manajemen Informatika',
            'email'=> 'farishakim@unesa.ac.id',
            'tanggal_lahir'=> '1995-08-16',
            'jenis_kelamin'=> 'L',
            'status'=> 'Dosen Tetap',
            'bidang_keahlian'=> 'Pemrograman Web',
            'created_at'=> Carbon::now(),
        ]);
    }
}
