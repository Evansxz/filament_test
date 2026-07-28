<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    Public function user()
    {
        return $this->belongsTo(User::class);
    }

    Public function detail()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}