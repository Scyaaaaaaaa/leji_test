<?php

namespace App\Models;

use App\Models\Repositories\RepositoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildingCase extends Model
{
    use HasFactory, RepositoryTrait;

    protected $fillable = [
        'type',
        'manager_id',
        'name',
    ];

    public function manager(){
        return $this->belongsTo('App\Models\Manager', 'manager_id');
    }

    public function tradings(){
        return $this->hasMany('App\Models\BuildingCaseTrading', 'case_id');
    }

}
