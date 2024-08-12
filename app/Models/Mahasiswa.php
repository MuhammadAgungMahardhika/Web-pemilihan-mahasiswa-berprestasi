<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class, 'id_mahasiswa');
    }
    public function dokumen_prestasi()
    {
        return $this->hasOne(DokumenPrestasi::class, 'id_mahasiswa');
    }
    public function departmen()
    {
        return $this->belongsTo(Departmen::class, 'id_departmen');
    }

    public function utusan()
    {
        return $this->hasMany(Utusan::class, 'id_mahasiswa');
    }
}
