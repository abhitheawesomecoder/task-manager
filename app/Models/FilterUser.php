<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterUser extends Model
{
    use HasFactory;

    protected $table = 'filter_user';

    protected $guarded = [];
}
