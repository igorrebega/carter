<?php

namespace App\Http\Controllers;

class CarController extends Controller
{
    public function getInfo($number)
    {
        return [
            'model'     => 'Skoda',
            'brand'     => 'Fabia',
            'year'      => '2011',
            'color'     => 'Білий',
            'body'      => 'Хеджбек',
            'fuel'      => 'Дизельне паливо',
            'capacity'  => '2696',
            'weight'    => '2069',
            'number'    => 'АВ1923ВТ',
            'avg_price' => '7000',
        ];
    }
}
