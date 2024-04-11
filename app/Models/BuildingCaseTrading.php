<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingCaseTrading extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'price'
    ];

    public function buildingCase()
    {
        return $this->belongsTo('App\Models\BuildingCase', 'case_id');
    }

    public function highestPrice()
    {
        return $this->hasOne('App\Models\HighestPriceRecord', 'trading_id');
    }

}
