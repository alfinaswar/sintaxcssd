<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetManagemenModel extends Model
{
    use HasFactory, SoftDeletes;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_managemen';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'departemen',
        'userPengguna',
        'mac',
        'ipID',
        'noIP',
        'os',
        'jenis',
        'keterangan',
        'status',
        'password'
    ];
}
