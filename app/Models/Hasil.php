<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
    protected $table = 'tabel_hasil';
    protected $primaryKey = 'id_hasil';
    protected $guarded = [];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'id_alternatif');
    }
}
