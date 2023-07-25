<?php

declare(strict_types=1);

namespace Effectra\Http\Foundation;

use Effectra\Http\Message\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Represents the Request Foundation implementation.
 */
class RequestFoundation
{

    /**
     * Creates a ServerRequestInterface instance from global variables.
     *
     * @return ServerRequestInterface The created ServerRequestInterface instance.
     */
    public static function createFromGlobals(): ServerRequestInterface
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $headers = self::getHeaders();
        $uri = UriFoundation::getFromGlobals();
        $body =  StreamFoundation::create();
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';
        $files = UploadedFileFoundation::createFromGlobals();

        $serverRequest = new ServerRequest($method, $uri, $headers, $body, $protocol, $_GET, $_POST,  $_SERVER);
        $serverRequest
            ->withCookieParams($_COOKIE)
            ->withUploadedFiles($files);

        return $serverRequest;
    }

    /**
     * Retrieves the headers from the global $_SERVER variable.
     *
     * @return array The headers.
     */
    private static function getHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))); // Convert header name to "Proper-Case" format
                $headers[$name] = is_array($value) ? implode(',', $value) : $value;
            }
        }
        return $headers;
    }
}
