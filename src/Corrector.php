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
}
