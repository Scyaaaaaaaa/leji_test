<?php


namespace App\Models\Repositories;

use App\Models\BuildingCase;
use App\Models\BuildingCaseTrading;
use App\Models\Repositories\AbstractEloquentRepository;

class BuildingCaseRepository extends AbstractEloquentRepository
{
    public function __construct(BuildingCase $model)
    {
        $this->model = $model;
    }

    public function getEachCaseMostPrice($type = '大樓', $department = '資料部')
    {
//        $case = $this->model->join('building_case_tradings', 'building_case_tradings.case_id', '=', 'building_cases.id')
//            ->join('managers', 'managers.id', '=', 'building_cases.manager_id')
//            ->where('building_cases.type', '大樓')
//            ->where('managers.department', '資料部')
//            ->select('building_cases.id', 'building_case_tradings.price')
//            ->groupBy('building_cases.id')
//            ->havingRaw('MAX(building_case_tradings.price) > 0');


        $case = $this->model->with(['tradings','manager'])
            ->whereHas('manager', function ($query) use ($department) {
                $query->where('department', $department);
            })->where('type', $type)
            ->selectRaw('building_cases.id, building_cases.name, building_cases.type , building_cases.manager_id')
            ->groupBy('building_cases.id')
            ->get();

        return $case;
    }
}
