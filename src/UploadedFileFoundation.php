<?php

declare(strict_types=1);

namespace Effectra\Http\Foundation;

use Psr\Http\Message\StreamInterface;

/**
 * Represents the Uploaded File Foundation implementation.
 */
class UploadedFileFoundation 
{
    /**
     * UploadedFileFoundation constructor.
     *
     * @param StreamInterface $stream The underlying stream representing the uploaded file.
     * @param int $size The size of the uploaded file in bytes.
     * @param int $error The PHP file upload error code.
     * @param string|null $clientFilename The client-provided filename of the uploaded file.
     * @param string|null $clientMediaType The client-provided media type of the uploaded file.
     */
    public function __construct(
        private StreamInterface $stream,
        private int $size,
        private int $error,
        private ?string $clientFilename = null,
        private ?string $clientMediaType = null
    ) {
    }

    /**
     * Retrieves the underlying stream representing the uploaded file.
     *
     * @return StreamInterface The underlying stream representing the uploaded file.
     */
    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    /**
     * Retrieves the size of the uploaded file in bytes.
     *
     * @return int The size of the uploaded file in bytes.
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Retrieves the PHP file upload error code.
     *
     * @return int The PHP file upload error code.
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Retrieves the client-provided filename of the uploaded file.
     *
     * @return string|null The client-provided filename of the uploaded file.
     */
    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    /**
     * Retrieves the client-provided media type of the uploaded file.
     *
     * @return string|null The client-provided media type of the uploaded file.
     */
    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
