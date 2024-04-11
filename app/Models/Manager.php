<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $fillable = [
        'department',
        'name'
    ];

    public function BulidingCases(){
        return $this->hasMany('App\Models\BuildingCase', 'manager_id');
    }
}
