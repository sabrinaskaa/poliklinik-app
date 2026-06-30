<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    
    protected $fillable = ['nama_obat', 'kemasan', 'harga', 'stok'];

    /**
     * Cek apakah stok menipis (kurang dari 10).
     */
    public function stokMenipis(): bool
    {
        return $this->stok > 0 && $this->stok < 10;
    }

    /**
     * Cek apakah stok sudah habis.
     */
    public function stokHabis(): bool
    {
        return $this->stok <= 0;
    }

    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_obat');
    }
}

