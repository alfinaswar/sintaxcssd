<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulirPembersihan extends Model
{
    use HasFactory;

    protected $table = 'formulir_pembersihans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Get the user that owns the FormulirPembersihan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getuser()
    {
        return $this->belongsTo(User::class, 'createdBy', 'id');
    }
    /**
     * Get the inventaris associated with the FormulirPembersihan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getInventaris()
    {
        return $this->belongsTo(DataInventaris::class, 'kode_item', 'kode_item');
    }
}
