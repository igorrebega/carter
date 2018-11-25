<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarController extends Controller
{
    public function getInfo(Request $request)
    {
        \Log::info($request->all());
        \Log::info($request->headers->all());
        $number = $request->get('number');
        $number = $this->convert($number);
        \Log::info($number);

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
            'а',
            'б',
            'в',
            'г',
            'д',
            'е',
            'ё',
            'ж',
            'з',
            'и',
            'й',
            'к',
            'л',
            'м',
            'н',
            'о',
            'п',
            'р',
            'с',
            'т',
            'у',
            'ф',
            'х',
            'ц',
            'ч',
            'ш',
            'щ',
            'ъ',
            'ы',
            'ь',
            'э',
            'ю',
            'я',
            'А',
            'Б',
            'В',
            'Г',
            'Д',
            'Е',
            'Ё',
            'Ж',
            'З',
            'I',
            'Й',
            'К',
            'Л',
            'М',
            'Н',
            'О',
            'П',
            'Р',
            'С',
            'Т',
            'У',
            'Ф',
            'Х',
            'Ц',
            'Ч',
            'Ш',
            'Щ',
            'I',
            'І',
            'Ь',
            'Э',
            'Ю',
            'Я',
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
            'О'
        ];
        $lat = [
            'a',
            'b',
            'v',
            'g',
            'd',
            'e',
            'io',
            'zh',
            'z',
            'i',
            'y',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'r',
            's',
            't',
            'u',
            'f',
            'h',
            'ts',
            'ch',
            'sh',
            'sht',
            'a',
            'i',
            'y',
            'e',
            'yu',
            'ya',
            'A',
            'B',
            'V',
            'G',
            'D',
            'E',
            'Io',
            'Zh',
            'Z',
            'I',
            'Y',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'R',
            'S',
            'T',
            'U',
            'F',
            'H',
            'Ts',
            'Ch',
            'Sh',
            'Sht',
            'A',
            'I',
            'Y',
            'e',
            'Yu',
            'Ya',
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
            'O'
        ];
        $input = str_replace($lat, $cyr, $input);

        return $input;
    }
}
