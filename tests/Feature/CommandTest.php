<?php

namespace Tests\Feature;

use App\Models\BuildingCase;
use App\Models\BuildingCaseTrading;
use App\Models\HighestPriceRecord;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_command(): void
    {
        $manager1 = Manager::factory()->create([
            'department' => '資料部'
        ]);

        $buildingCase1 = BuildingCase::factory()->create([
            'manager_id' => $manager1->id,
            'type' => '大樓'
        ]);
        $buildingCase2 = BuildingCase::factory()->create([
            'manager_id' => $manager1->id,
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
        $buildingCaseTrading4 = BuildingCaseTrading::factory()->create([
            'case_id' => $buildingCase2->id,
            'price' => 4000000,
        ]);
        Artisan::call('app:daily-store-highest-price');
        $highestPrice1 = HighestPriceRecord::all()->where('case_id',$buildingCase1->id)->first();

        $this->assertEquals(2000000, $highestPrice1->highest_price);
        $this->assertEquals('大樓', $highestPrice1->case_type);
        $this->assertEquals('資料部', $highestPrice1->manager_department);

        $highestPrice2 = HighestPriceRecord::all()->where('case_id',$buildingCase2->id)->first();
        $this->assertEquals(50000000, $highestPrice2->highest_price);
        $this->assertEquals('大樓', $highestPrice2->case_type);
        $this->assertEquals('資料部', $highestPrice2->manager_department);


        //在增加一筆更高的成交價驗證是排程是否會總是找到最高的成交價
        $buildingCaseTrading5 = BuildingCaseTrading::factory()->create([
            'case_id' => $buildingCase2->id,
            'price' => 10000000000,
        ]);
        Artisan::call('app:daily-store-highest-price');
        $highestPrice2 = HighestPriceRecord::all()->where('case_id',$buildingCase2->id)->first();
        $this->assertEquals(10000000000, $highestPrice2->highest_price);
        $this->assertEquals('大樓', $highestPrice2->case_type);
        $this->assertEquals('資料部', $highestPrice2->manager_department);
    }
}
