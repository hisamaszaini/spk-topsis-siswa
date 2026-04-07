<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'tabel_alternatif';
    protected $primaryKey = 'id_alternatif';
    protected $guarded = [];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_alternatif');
    }
}
