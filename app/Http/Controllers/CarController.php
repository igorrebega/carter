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
            'model'     => $car->carModel->name,
            'brand'     => $car->brand->name,
            'year'      => $car->year,
            'color'     => $car->color->name,
            'body'      => $car->body->name,
            'fuel'      => $car->fuel->name,
            'capacity'  => $car->capacity,
            'weight'    => $car->own_weight,
            'number'    => $number,
            'avg_price' => 0,
        ];
    }
}
