<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighestPriceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'trading_id',
        'case_type',
        'case_name',
        'manager_name',
        'manager_department',
        'highest_price'
    ];

    public function trading()
    {
        return $this->belongsTo('App\Models\BuildingCaseTrading', 'trading_id');
    }
}
