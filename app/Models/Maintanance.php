<?php

namespace App\Models;

use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintanance extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maintanance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

}
