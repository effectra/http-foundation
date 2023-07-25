<?php

declare(strict_types=1);

namespace Effectra\Http\Foundation;

use Effectra\Http\Message\Stream;

/**
 * Class StreamFoundation
 *
 * This class provides a factory method for creating instances of the Stream class.
 *
 * @package Effectra\Http\Foundation
 */
 
class StreamFoundation
{
    /**
     * Create a new Stream instance.
     *
     * @return Stream The created Stream instance.
     */
    public static function create(): Stream
    {
        $resource = fopen('php://temp', 'r+');

        fwrite($resource, file_get_contents('php://input'));

        rewind($resource);

        return new Stream($resource);
    }
}
