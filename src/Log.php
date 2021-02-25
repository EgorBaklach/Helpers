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

        return self::$file === false;
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

        if(self::$file === false)
        {
            self::$file = self::$path.'/log_'.date('Ymd_His').'.log';
        }
    }

    private static function getContent($content): string
    {
        switch(gettype($content))
        {
            case 'object':
            case 'array':
                $content = (array) $content;

                foreach($content as $key => &$value)
                {
                    $value = $key." => ".self::getContent($value).PHP_EOL;
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

        return $content;
    }

    public static function add2log($content, $one_string = false): bool
    {
        if(self::checkFile())
        {
            self::setFile();
        }

        $arBacktrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $content = self::getContent($content);

        if(!$one_string)
        {
            $content = implode(PHP_EOL, [
                date('Y-m-d H:i:s'),
                $arBacktrace[0]['file'].' on line '.$arBacktrace[0]["line"].':',
                $content,
                "--------------------"
            ]);
        }

        if(file_put_contents(static::$file, $content.PHP_EOL, FILE_APPEND | LOCK_EX) === false)
        {
            return false;
        }

        return true;
    }
}