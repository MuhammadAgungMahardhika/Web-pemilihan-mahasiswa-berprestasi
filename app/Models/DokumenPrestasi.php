<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPrestasi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function capaian_unggulan()
    {
        return $this->belongsTo(CapaianUnggulan::class, 'id_capaian_unggulan');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
}
