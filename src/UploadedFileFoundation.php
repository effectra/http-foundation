<?php

declare(strict_types=1);

namespace Effectra\Http\Foundation;

use Effectra\Http\Message\UploadedFile;

class UploadedFileFoundation
{
    /**
     * Normalizes the uploaded files array.
     *
     * @param array $files The uploaded files array.
     * @return array The normalized files array.
     */
    public static function createFromGlobals():array
    {
        $normalizedFiles = [];
        foreach ($_FILES as $key => $file) {
            $normalizedFiles[$key] = new UploadedFile(
                $file['tmp_name'],
                $file['size'],
                $file['error'],
                $file['name'],
                $file['type']
            );
        }
        return  $normalizedFiles;
    }
}