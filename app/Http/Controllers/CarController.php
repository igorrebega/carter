<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarController extends Controller
{
    public function getInfo(Request $request)
    {
//        $number = $request->get('number');
//
//        $car = Car::query()->where(['number' => $number])->first();
//
//        if (! $car) {
//            throw new NotFoundHttpException();
//        }

        return [
            'model'     => 'Fabia',
            'brand'     => 'Skoda',
            'year'      => 'd',
            'color'     => 'c',
            'body'      => 'c',
            'fuel'      => 'c',
            'capacity'  => '1',
            'weight'    => '1',
            'number'    => 'c',
            'avg_price' => 0,
        ];
    }
}
