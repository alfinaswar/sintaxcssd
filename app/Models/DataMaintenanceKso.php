<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataMaintenanceKso extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maintenance_ksos';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke user pembuat.
     */
    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    /**
     * Relasi balik ke InventarisKso.
     */
    public function getInventaris()
    {
        return $this->belongsTo(InventarisKso::class, 'inventaris_kso_id', 'id');
    }
}
