<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'priority',
        'tags',
        'status'
    ];
}
