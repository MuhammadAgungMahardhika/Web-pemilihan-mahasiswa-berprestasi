<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKaryaIlmiah extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function karya_ilmiah()
    {
        return $this->belongsTo(KaryaIlmiah::class, 'id_karya_ilmiah');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
