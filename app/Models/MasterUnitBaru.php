<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterUnitBaru extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_unit_barus';
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the user associated with the MasterUnitBaru
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getRs()
    {
        return $this->hasOne(MasterRs::class, 'kodeRS', 'KodeRs');
    }
}
