<?php

declare(strict_types=1);

namespace Effectra\Http\Foundation;

use Effectra\Contracts\Http\RequestFoundationInterface;
use Effectra\Http\Message\ServerRequest;
use Effectra\Http\Message\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Represents the Request Foundation implementation.
 */
class RequestFoundation implements RequestFoundationInterface
{
    /**
     * RequestFoundation constructor.
     *
     * @param array $getParams The GET parameters.
     * @param array $postParams The POST parameters.
     * @param array $cookies The cookies.
     * @param array $files The uploaded files.
     * @param array $server The server variables.
     */
    public function __construct(
        public array $getParams,
        public array $postParams,
        public array $cookies,
        public array $files,
        public array $server
    ) {
    }

    /**
     * Creates a ServerRequestInterface instance from global variables.
     *
     * @return ServerRequestInterface The created ServerRequestInterface instance.
     */
    public static function createFromGlobals(): ServerRequestInterface
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $headers = self::getHeaders();
        $uri = self::getUriFromGlobals();
        $body = (string) file_get_contents('php://input');
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';

        $serverRequest = new ServerRequest(
            method: $method,
            uri: $uri,
            headers: $headers,
            body: $body,
            queryParams: $_GET,
            parsedBody: $_POST,
            protocolVersion: $protocol,
            attributes: $_SERVER,
        );
        $serverRequest
            ->withCookieParams($_COOKIE)
            ->withUploadedFiles(self::normalizeFiles($_FILES));

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

    /**
     * Retrieves the URI from the global $_SERVER variable.
     *
     * @return UriInterface The URI.
     */
    private static function getUriFromGlobals(): UriInterface
    {
        $uri = new Uri('');
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $uri = $uri->withScheme('https');
        } else {
            $uri = $uri->withScheme('http');
        }

        $hasPort = false;
        if (isset($_SERVER['HTTP_HOST'])) {
            $hostHeaderParts = explode(':', $_SERVER['HTTP_HOST']);
            $host = array_shift($hostHeaderParts);
            $uri = $uri->withHost($host);
            if (count($hostHeaderParts) > 0) {
                $port = (int) array_shift($hostHeaderParts);
                if ($port > 0 && $port <= 65535) {
                    $uri = $uri->withPort($port);
                    $hasPort = true;
                }
            }
        }

        if (!$hasPort && isset($_SERVER['SERVER_PORT'])) {
            $serverPort = (int) $_SERVER['SERVER_PORT'];
            if (($uri->getScheme() === 'http' && $serverPort !== 80) || ($uri->getScheme() === 'https' && $serverPort !== 443)) {
                $uri = $uri->withPort($serverPort);
            }
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $requestUriParts = explode('?', $_SERVER['REQUEST_URI'], 2);
            $path = array_shift($requestUriParts);
            $query = count($requestUriParts) > 0 ? $requestUriParts[0] : '';
            $uri = $uri->withPath($path)->withQuery($query);
        }

        return $uri;
    }

    /**
     * Normalizes the uploaded files array.
     *
     * @param array $files The uploaded files array.
     * @return array The normalized files array.
     */
    private static function normalizeFiles(array $files): array
    {
        $normalizedFiles = [];
        foreach ($files as $key => $value) {
            if (is_array($value['name'])) {
                foreach ($value['name'] as $index => $name) {
                    $normalizedFiles[$key][$index] = [
                        'name' => $name,
                        'type' => $value['type'][$index],
                        'tmp_name' => $value['tmp_name'][$index],
                        'error' => $value['error'][$index],
                        'size' => $value['size'][$index],
                    ];
                }
            } else {
                $normalizedFiles[$key] = $value;
            }
        }
        return $normalizedFiles;
    }
}
