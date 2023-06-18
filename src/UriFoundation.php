<?php

declare(strict_types=1);

namespace Effectra\Http\Foundation;

use Effectra\Http\Message\Uri;
use Psr\Http\Message\UriInterface;

class UriFoundation
{
    /**
     * Retrieves the URI from the global $_SERVER variable.
     *
     * @return UriInterface The URI.
     */
    public static function getFromGlobals(): UriInterface
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
}