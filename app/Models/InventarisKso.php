<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class InventarisKso extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventaris_ksos';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Boot the model and automatically generate KodeBarang in format KSO-000001, etc.
     * Pastikan tidak ada balapan kode dengan atomic locking.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->KodeBarang)) {
                DB::transaction(function () use ($model) {
                    $tableName = $model->getTable();
                    $lastRecord = DB::table($tableName)
                        ->select('id')
                        ->orderByDesc('id')
                        ->lockForUpdate()
                        ->first();

                    $nextNumber = $lastRecord ? ($lastRecord->id + 1) : 1;
                    $kode = 'KSO-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                    $model->KodeBarang = $kode;
                });
            }
        });
    }

    /**
     * Define relationship to NamaAlat.
     */
    public function getNamaAlat()
    {
        return $this->belongsTo(MasterAlat::class, 'Nama');
    }

    /**
     * Define relationship to Merk.
     */
    public function getMerk()
    {
        return $this->belongsTo(MasterMerk::class, 'Merk');
    }

    /**
     * Define relationship to RS.
     */
    public function getRS()
    {
        return $this->hasOne(MasterRs::class, 'kodeRS', 'NamaRS');
    }

    /**
     * Define relationship to Departemen.
     */
    public function getDepartemen()
    {
        return $this->belongsTo(MasterDepartemenModel::class, 'Departemen');
    }
}
