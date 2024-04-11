<?php


namespace App\Models\Repositories;

use App\Models\BuildingCase;
use App\Models\BuildingCaseTrading;
use App\Models\HighestPriceRecord;
use App\Models\Repositories\AbstractEloquentRepository;

class HighestPriceRecordRepository extends AbstractEloquentRepository
{
    public function __construct(HighestPriceRecord $model)
    {
        $this->model = $model;
    }


}
