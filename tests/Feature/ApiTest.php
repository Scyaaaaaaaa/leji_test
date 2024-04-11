<?php

namespace Tests\Feature;

use App\Models\BuildingCase;
use App\Models\BuildingCaseTrading;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;


class ApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_api_get_(): void
    {
        $manager1 = Manager::factory()->create([
            'department' => '資料部'
        ]);
        $manager2 = Manager::factory()->create([
            'department' => '工程部'
        ]);

        $buildingCase1 = BuildingCase::factory()->create([
            'manager_id' => $manager1->id,
            'type' => '大樓'
        ]);
        $buildingCase2 = BuildingCase::factory()->create([
            'manager_id' => $manager2->id,
            'type' => '大樓'
        ]);

        $buildingCaseTrading1 = BuildingCaseTrading::factory()->create([
            'case_id' => $buildingCase1->id,
            'price' => 1000000,
        ]);

        $buildingCaseTrading2 = BuildingCaseTrading::factory()->create([
            'case_id' => $buildingCase1->id,
            'price' => 2000000,
        ]);

        $buildingCaseTrading3 = BuildingCaseTrading::factory()->create([
            'case_id' => $buildingCase2->id,
            'price' => 50000000,
        ]);

        $response = $this->get('building-case/highest-price');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'items' => [
                '*' => [
                    'building_case_id',
                    'building_case_name',
                    'highest_trading_price',
                ],
            ],
        ]);

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('000000', $responseData['status']);
        $this->assertEquals('Success', $responseData['message']);


        $caseData1 = $responseData['items'][0];
        $this->assertEquals($buildingCase1->id, $caseData1['building_case_id']);
        $this->assertEquals($buildingCase1->name, $caseData1['building_case_name']);
        $this->assertEquals(2000000, $caseData1['highest_trading_price']);


    }
}
