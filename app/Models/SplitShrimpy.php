<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SplitShrimpy extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function my_coin()
    {
        return $this->hasOne(MyCoin::class);
    }
}
