<?php

declare(strict_types=1);

namespace Effectra\Http\Foundation;

use Effectra\Contracts\Http\ResponseFoundationInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Represents the Response Foundation implementation.
 */
class ResponseFoundation 
{
    /**
     * ResponseFoundation constructor.
     *
     * @param string|null $content The response content.
     * @param int $code The response status code.
     * @param array|null $headers The response headers.
     */
    public function __construct(
        private ?string $content = '',
        private int $code = 200,
        private ?array $headers = []
    ) {
    }

    /**
     * Sends the response.
     *
     * @param ResponseInterface $response The response to send.
     */
    public static function send(ResponseInterface $response): void
    {
        // Set the response headers
        foreach ($response->getHeaders() as $headerName => $headerValues) {
            $headerValues = implode(', ', $headerValues);
            header(sprintf('%s: %s', $headerName, $headerValues));
        }

        // Set the response status code
        $statusCode = $response->getStatusCode();
        header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $statusCode, $response->getReasonPhrase()), true, $statusCode);

        // Send the response body
        echo $response->getBody();
    }
}
