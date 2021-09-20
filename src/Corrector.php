<?php namespace Helpers;

class Corrector
{
    static function Framing($value, $spec = false): string
    {
        return $spec.$value.$spec;
    }

    static function RoundFraming($value, $spec = false): string
    {
        return '('.self::Framing($value, $spec).')';
    }

    static function num2word($number, $after): string
    {
        return $after[($number%100 > 4 && $number%100 < 20) ? 2 : [2,0,1,1,1,2][min($number%10, 5)]];
    }

    static function cyrmonth($date): string
    {
        $months = [
            'ru' => ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'],
            'en' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        ];

        return str_replace($months['en'], $months['ru'], $date);
    }
}
