<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarController extends Controller
{
    public function getInfo(Request $request)
    {
        $number = $request->get('number');

        $car = Car::query()->where(['number' => $number])->first();

        if (! $car) {
            throw new NotFoundHttpException();
        }

        return [
            'model'     => 'Skoda',
            'brand'     => 'Fabia',
            'year'      => 2012,
            'color'     => 'Білий',
            'body'      => 'Хеджбек',
            'fuel'      => 'Дизель',
            'capacity'  => '1580',
            'weight'    => '1115',
            'number'    => 'AA8631EH',
            'avg_price' => 0,
        ];
    }
}
