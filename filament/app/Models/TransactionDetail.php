<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $guarded = [];

    Public function detail()
    {
        return $this->hasMany(Transaction::class);
    }

    Public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
