<?namespace Helpers;

final class Log
{
    private static $path = 'logs';
    private static $file = false;

    const size = 34000;

    private static function checkFile(): bool
    {
        if(filesize(self::$file) >= self::size)
        {
            self::$file = false;
        }

        return empty(self::$file);
    }

    private static function setFile(): void
    {
        if(!is_dir(self::$path))
        {
            mkdir(self::$path, 0775);
        }

        $files = scandir(self::$path);

        rsort($files);

        foreach($files as $file)
        {
            if(!preg_match('/^log_.*\.log$/i', $file))
            {
                continue;
            }

            self::$file = self::$path.'/'.$file;

            self::checkFile();

            break;
        }

        if(empty(self::$file))
        {
            self::$file = self::$path.'/log_'.date('Ymd_His').'.log';
        }
    }

    private static function getContent($content)
    {
        switch(gettype($content))
        {
            case 'object':
            case 'array':
                $content = (array) $content;

                foreach($content as $key => &$value)
                {
                    $value = $key." => ".self::getContent($value)."\r";
                }

                $content = implode('', $content);
                break;
            case 'boolean':
                $content = $content ? 'true' : 'false';
                break;
            case 'NULL':
                $content = 'NULL';
                break;
        }

        if(empty($content) && $content != '0')
        {
            $content = "Value are empty";
        }

        return $content;
    }

    public static function add2log($content, $one_string = false)
    {
        if(self::checkFile())
        {
            self::setFile();
        }

        $arBacktrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $content = self::getContent($content);

        if(!$one_string)
        {
            $content = implode('', [
                date('Y-m-d H:i:s'),
                "\r",
                $arBacktrace[0]['file'].' on line '.$arBacktrace[0]["line"].':',
                "\r",
                $content,
                "\r--------------------\r"
            ]);
        }

        if(file_put_contents(static::$file, $content, FILE_APPEND | LOCK_EX) === false)
        {
            return false;
        }

        return true;
    }
}