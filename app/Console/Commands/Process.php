<?php

namespace App\Console\Commands;

use App\Models\Body;
use App\Models\Brand;
use App\Models\BrandSynonym;
use App\Models\Car;
use App\Models\Color;
use App\Models\Fuel;
use App\Models\Kind;
use App\Models\Model;
use Illuminate\Console\Command;

class Process extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $handle = fopen(storage_path('app/data/2013.csv'), 'rb');
        $header = true;

        $i = 0;

        while ($csvLine = fgetcsv($handle, 0, ';')) {
            if ($header) {
                $header = false;
            } else {
                $i++;

                if ($csvLine[13] == 'СПЕЦІАЛІЗОВАНИЙ') {
                    continue;
                }

                $car = Car::query()->where('number', $csvLine[18])->exists();
                if ($car) {
                    continue;
                }

                $fuel = $csvLine[14];
                if ($fuel == 'NULL') {
                    continue;
                }

                $ownWeight = $csvLine[16];
                if ($ownWeight == 'NULL') {
                    continue;
                }

                $capacity = $csvLine[15];
                if ($capacity == 'NULL') {
                    continue;
                }

                $brandId = $this->getBrandId($csvLine['7']);
                $modelId = $this->getModelId($csvLine['8'], $brandId);
                $colorId = $this->getColorId($csvLine['10']);
                $fuelId = $this->getFuelId($csvLine['14']);
                $kindId = $this->getKindId($csvLine['11']);
                $bodyId = $this->getBodyId($csvLine['12']);

                $car = new Car();
                $car->model_id = $modelId;
                $car->brand_id = $brandId;
                $car->color_id = $colorId;
                $car->fuel_id = $fuelId;
                $car->year = $csvLine[9];
                $car->capacity = $csvLine[15];
                $car->kind_id = $kindId;
                $car->body_id = $bodyId;
                $car->own_weight = $csvLine[16];
                $car->number = $csvLine[18];

                $car->save();

                echo $i . PHP_EOL;

            }
        }
    }


    private function getColorId($colorFromFile)
    {
        $color = Color::query()->where('name', $colorFromFile)->first();

        if (! $color) {
            $color = Color::query()->create(['name' => $colorFromFile]);
        }

        return $color->id;
    }

    private function getFuelId($fuelFromFile)
    {
        $fuel = Fuel::query()->where('name', $fuelFromFile)->first();

        if (! $fuel) {
            $fuel = Fuel::query()->create(['name' => $fuelFromFile]);
        }

        return $fuel->id;
    }

    private function getKindId($kindFromFile)
    {
        $kind = Kind::query()->where('name', $kindFromFile)->first();

        if (! $kind) {
            $kind = Kind::query()->create(['name' => $kindFromFile]);
        }

        return $kind->id;
    }

    private function getBodyId($bodyFromFile)
    {
        $body = Body::query()->where('name', $bodyFromFile)->first();

        if (! $body) {
            $body = Body::query()->create(['name' => $bodyFromFile]);
        }

        return $body->id;
    }

    private function getBrandId($brandFromFile)
    {
        $brands = Brand::query()->pluck('name', 'id');

        foreach ($brands as $id => $brand) {
            if (stripos($brandFromFile, $brand) !== false) {
                return $id;
            }
        }

        $brandSynonyms = BrandSynonym::query()->get();

        foreach ($brandSynonyms as $brand) {

            if (stripos($brandFromFile, $brand->name) !== false) {

                if (! $brand->brand_id) {
                    return null;
                }

                return $brand->brand_id;
            }
        }

        $brand = Brand::query()->create(['name' => explode(' ', $brandFromFile)[0]]);

        return $brand->id;
    }

    private function getModelId($modelFromFile, $brandId)
    {
        if (! $brandId) {
            return null;
        }

        $model = Model::query()->where('brand_id', $brandId)->where('name', $modelFromFile)->first();
        if ($model) {
            return $model->id;
        }

        $model = Model::query()->create(['brand_id' => $brandId, 'name' => $modelFromFile]);

        return $model->id;
    }

}