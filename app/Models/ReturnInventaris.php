<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnInventaris extends Model
{
    use HasFactory;

    protected $table = 'return_inventaris';
    protected $guarded = ['id'];
}
