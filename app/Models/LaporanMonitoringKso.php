<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMonitoringKso extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'formulir_pembersihan_ksos';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke user pembuat formulir.
     */
    public function getUser()
    {
        return $this->belongsTo(User::class, 'createdBy', 'id');
    }

    /**
     * Relasi balik ke InventarisKso.
     */
    public function getInventaris()
    {
        return $this->belongsTo(InventarisKso::class, 'inventaris_kso_id', 'id');
    }
}
