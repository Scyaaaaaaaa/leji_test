<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Repositories\BuildingCaseRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class BuildingCaseController extends Controller
{
    private $buildingCase;
    public function __construct(
        BuildingCaseRepository $buildingCase,
    )
    {
        $this->buildingCase = $buildingCase;
    }

    public function getMostExpensiveForEachCases()
    {
        try {
            $cases = $this->buildingCase->getEachCaseMostPrice();


            foreach ($cases as $key => $case){
                $cases[$key]->highest_price = $case->tradings->max('price');
                $cases[$key]->manager_department = $case->manager->department;
            }

            $cases = $cases->map(function ($case){
                return [
                    'building_case_id' => $case->id,
                    'building_case_name' => $case->name,
                    'highest_trading_price' => $case->highest_price,
                    'building_case_type' => $case->type,
                    'manager_department' => $case->manager_department
                ];
            });

            return response()->json([
                'status' => '000000',
                'message' => 'Success',
                'items' => $cases
            ]);
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return response()->json([
                'status' => '000010',
                'message' => 'Fail'
            ]);
        }
    }
}
