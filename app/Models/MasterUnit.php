<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterUnit extends Model
{
    use HasFactory;
    protected $table = 'master_units';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idDepartemen',
        'namaUnit',
        'nama_rs',
    ];
    public function DataMaintenance()
    {
        return $this->hasMany(Maintanance::class, 'kode_item', 'kode_item');
    }
}
