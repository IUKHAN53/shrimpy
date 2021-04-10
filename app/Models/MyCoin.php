<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyCoin extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function binance_coin(){
        return $this->belongsTo(BinanceCoin::class);
    }

    public function split_shrimpy()
    {
        return $this->hasOne(SplitShrimpy::class);
    }

    public function usdt(){
        return $this->binance_coin()->where('symbol','USDT');
    }

}
