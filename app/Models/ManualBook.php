<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualBook extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'manual_books';
    protected $guarded = ['id'];
}
