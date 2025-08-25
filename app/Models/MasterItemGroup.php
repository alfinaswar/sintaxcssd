<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterItemGroup extends Model
{
    use HasFactory;
    protected $table = 'cssd_master_item_groups';

    protected $guarded = ['id'];

    /**
     * Get the user that owns the MasterItemGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getMerk()
    {
        return $this->belongsTo(cssdMerk::class, 'Merk', 'id');
    }

    public function getListItem()
    {
        return $this->hasMany(cssdMasterItem::class, 'Nama', 'id');
    }
    public function getInUse()
    {
        return $this->hasMany(cssdItemsetDetail::class, 'ItemId', 'id');
    }

    public function getListItemCount()
    {
        return $this->hasMany(cssdMasterItem::class, 'Nama');
    }

}
