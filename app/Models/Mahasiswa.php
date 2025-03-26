<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // Nama tabel di database
    protected $primaryKey = 'nim'; // Primary key tabel
    public $incrementing = false; // Primary key bukan auto-increment
    protected $keyType = 'string'; // Tipe data primary key adalah string
    protected $fillable = ['nim', 'nama', 'jenis_kelamin', 'alamat', 'tanggal_lahir', 'program_studi', 'angkatan', 'email']; // Kolom yang boleh diisi (mass assignment)
}