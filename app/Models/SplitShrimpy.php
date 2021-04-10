<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SplitShrimpy extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function binance_coin(){
        return $this->belongsTo(BinanceCoin::class);
    }
}
