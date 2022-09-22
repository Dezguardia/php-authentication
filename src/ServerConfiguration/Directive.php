<?php

namespace ServerConfiguration;

use Html\Helper\Dumper;

class Directive
{
    public static function get(string $directive): string
    {
        return $directive;
    }

    public static function getInBytes(string $directive): int
    {
        $dir=self::get($directive);
        preg_match('/([0-9]+)([gmk]?)/i', $dir, $matches);
        $res = (int) $matches[1];
        switch ($matches[2]) {
            case 'G':
                $res*=1024;
            case 'M':
                $res*=1024;
            case 'K':
                $res*=1024;
        }
        return $res;
    }

    public static function getUploadMaxFileSize(): int
    {
        return self::getInBytes(ini_get('upload_max_filesize'));
    }
}
