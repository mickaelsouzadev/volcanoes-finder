<?php

namespace Mickaelsouzadev\VolcanoesFinder\Helper;

use Exception;

class FileHandler
{
    const BASE_PATH = '/../../data/';

    public function write($path, $content)
    {
        file_put_contents(__DIR__ . self::BASE_PATH . $path, $content);
    }

    public function read($path): string | false
    {
        try {
            return file_get_contents(__DIR__ . self::BASE_PATH . $path);
        } catch (Exception $e) {
            return false;
        }
    }
}
