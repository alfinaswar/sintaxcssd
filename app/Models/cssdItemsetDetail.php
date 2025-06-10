<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdItemsetDetail extends Model
{
    use HasFactory;
    protected $table = 'cssd_itemset_details';
    protected $guarded = ['id'];

    protected $casts = [
        'IdItemset' => 'array',
        'ItemId' => 'array',
        'Qty' => 'array',
    ];
    public function MasterItem()
    {
        return $this->belongsTo(cssdMasterItem::class, 'ItemId'); // relasi ke item master
    }
}
