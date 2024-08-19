<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryaIlmiah extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function penilaian_karya_ilmiah()
    {
        return $this->hasMany(PenilaianKaryaIlmiah::class, 'id_karya_ilmiah');
    }
}
