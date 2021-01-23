<?namespace Helpers;

class Date
{
    public static function correct($code)
    {
        return date($code, strtotime('-3 hours'));
    }
}