<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'icon',
        'url',
        'sort_order',
        'is_active'
    ];
}
