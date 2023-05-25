# Effectra\Http\Foundation

Effectra\Http\Foundation is a package that provides foundation classes for handling HTTP requests and responses.

## Installation

You can install this package via Composer. Run the following command in your terminal:

```bash
composer require effectra/http-foundation
```

## Classes

The package includes the following classes:

### RequestFoundation

The `RequestFoundation` class provides a foundation for creating PSR-7 compliant server request objects from PHP's global variables.

#### Usage

```php
use Effectra\Http\Foundation\RequestFoundation;
use Effectra\Contracts\Http\RequestFoundationInterface;

// Create a server request object from globals
$request = RequestFoundation::createFromGlobals();

// Access request attributes, query parameters, etc.
$path = $request->getUri()->getPath();
$queryParams = $request->getQueryParams();

// ... Add your code to handle the request ...
```

### ResponseFoundation

The `ResponseFoundation` class provides a foundation for sending PSR-7 compliant response objects.

#### Usage

```php
use Effectra\Http\Foundation\ResponseFoundation;
use Effectra\Contracts\Http\ResponseFoundationInterface;

// Create a response object
$response = new ResponseFoundation($content, $statusCode, $headers);

// Send the response
ResponseFoundation::send($response);
```

### UploadedFileFoundation

The `UploadedFileFoundation` class provides a foundation for representing uploaded files in HTTP requests.

#### Usage

```php
use Effectra\Http\Foundation\UploadedFileFoundation;
use Effectra\Contracts\Http\UploadedFileFoundationInterface;

// Create an uploaded file object
$uploadedFile = new UploadedFileFoundation($stream, $size, $error, $clientFilename, $clientMediaType);

// Access uploaded file properties
$stream = $uploadedFile->getStream();
$size = $uploadedFile->getSize();
$error = $uploadedFile->getError();
$clientFilename = $uploadedFile->getClientFilename();
$clientMediaType = $uploadedFile->getClientMediaType();

// ... Add your code to handle the uploaded file ...
```

## Contributing

Contributions are welcome! If you have any bug reports, feature requests, or suggestions, please open an issue on the [GitHub repository](https://github.com/effectra/http-foundation).

## License

This package is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
