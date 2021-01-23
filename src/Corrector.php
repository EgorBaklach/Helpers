<?php namespace Helpers;

class Corrector
{
    static function Framing($value, $spec = false)
    {
        return $spec.$value.$spec;
    }

    static function RoundFraming($value, $spec = false)
    {
        return '('.self::Framing($value, $spec).')';
    }
}
