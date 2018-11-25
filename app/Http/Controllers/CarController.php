<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use thiagoalessio\TesseractOCR\TesseractOCR;

class CarController extends Controller
{
    public function getNumber($image)
    {
        $result = $this->run('alpr --country eu --json ' . $image);

        $result = json_decode($result[0]);

        if (! isset($result->results[0])) {
            return '';
        }

        return $result->results[0]->plate;
    }

    function run($command)
    {
        $output = array();
        exec($command, $output);

        return $output;
    }

    public function getInfo(Request $request)
    {
        $name = time() . '.jpg';
        $request->file('imagefile')->storeAs('images', $name);

        $number = $this->getNumber(storage_path('app/images/' . $name));
        \Log::info('----');

        \Log::info($number);

        $number = $this->convert($number);
        $car = Car::query()->where(['number' => $number])->first();

        if (! $car) {
            \Log::info('can`t find car '. $number);

            throw new NotFoundHttpException();
        }

        \Log::info($car->carModel->name);


        $data = [
            'model'     => $car->carModel->name,
            'brand'     => $car->brand->name,
            'year'      => (string)$car->year,
            'color'     => $car->color->name,
            'body'      => $car->body->name,
            'fuel'      => $car->fuel->name,
            'capacity'  => (string) $car->capacity,
            'weight'    => (string)$car->own_weight,
            'number'    => $number,
            'avg_price' => '0',
        ];
        $headers = [ 'Content-Type' => 'application/json; charset=utf-8' ];

        return response()->json($data, 200, $headers, JSON_UNESCAPED_UNICODE);

        return $data;
    }

    private function convert($input)
    {
        $cyr = [
            'Х',
            'А',
            'Т',
            'М',
            'С',
            'В',
            'К',
            'Р',
            'Н',
            'Е',
            'О',
            'І'
        ];
        $lat = [
            'X',
            'A',
            'T',
            'M',
            'C',
            'B',
            'K',
            'P',
            'H',
            'E',
            'O',
            'I'
        ];
        $input = str_replace($lat, $cyr, $input);

        return $input;
    }
}
