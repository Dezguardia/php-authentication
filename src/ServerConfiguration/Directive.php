<?php

namespace ServerConfiguration;

use Html\Helper\Dumper;

class Directive
{
    /**
     * Retourne la valeur de la directive passée en paramètre.
     * @param string $directive
     * @return string
     */
    public static function get(string $directive): string
    {
        return $directive;
    }

    /**
     * Convertit la directive passée en paramètre en sa valeur en octets.
     * @param string $directive
     * @return int
     */
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

    /**
     * Retourne la taille maximale de fichiers uploadés à partir de php.ini
     * @return int
     */
    public static function getUploadMaxFileSize(): int
    {
        return self::getInBytes(ini_get('upload_max_filesize'));
    }
}
