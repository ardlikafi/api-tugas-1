<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;
    protected $table = 'dosen';
    protected $fillable = ['nama_dosen', 'nidn', 'email', 'program_studi', 'tanggal_lahir', 'jenis_kelamin', 'status'];
}
