<?php

namespace App\Console\Commands;

use App\Models\BuildingCase;
use App\Models\HighestPriceRecord;
use App\Models\Repositories\BuildingCaseRepository;
use App\Models\Repositories\HighestPriceRecordRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

class DailyStoreHighestPrice extends Command
{
    private $buildingCaseRepository;
    private $highestPriceRecord;

    public function __construct(
        BuildingCaseRepository $buildingCaseRepository,
        HighestPriceRecordRepository $highestPriceRecord
    )
    {
        parent::__construct();
        $this->buildingCaseRepository = $buildingCaseRepository;
        $this->highestPriceRecord = $highestPriceRecord;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-store-highest-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find and store highest price for each building cases';

    private $logClass = 'daily-store-highest-price';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('run');
            Log::info('Daily-store-highest-price command start');
            $cases = $this->buildingCaseRepository->getEachCaseMostPrice();

//            if ($cases == null){
//                throw new Exception('no cases found');
//            }

            foreach ($cases as $key => $case){
                $buildingCase = $this->buildingCaseRepository->find($case->id);
                $manager = $buildingCase->manager;
                $price = $case->tradings->max('price');
                $trading = $case->tradings->firstWhere('price', $price);

                $highestPriceRecord = HighestPriceRecord::all()->where('case_id',$buildingCase->id)->first();
                $data = [];
                $data['case_id'] = $buildingCase->id;
                $data['trading_id'] = $trading->id;
                $data['case_type'] = $buildingCase->type;
                $data['case_name'] = $buildingCase->name;
                $data['manager_name'] = $manager->name;
                $data['manager_department'] = $manager->department;
                $data['highest_price'] = $price;

                $highestPrice = $this->highestPriceRecord->createOrUpdate($data, $highestPriceRecord);
            }
            Log::info('Daily-store-highest-price command end');
        }catch (Exception $exception){
            Log::info('Daily-store-highest-price command wrong');
            Log::error($exception->getMessage());
        }

    }
}
