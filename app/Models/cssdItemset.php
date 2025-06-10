<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cssdItemset extends Model
{
    use HasFactory;
    protected $table = 'cssd_itemsets';
    protected $guarded = ['id'];


    public function DetailItem()
    {
        return $this->hasOne(cssdItemsetDetail::class, 'IdItemset', 'id');
    }

}
