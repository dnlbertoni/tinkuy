# Negotiation Middleware

[![Software License][ico-license]][license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coverage]][link-coverage]
[![Codacy Badge](https://api.codacy.com/project/badge/50c7a287c93644bda8bec08bce5e817d)](https://www.codacy.com/app/gofabian/negotiation-middleware)

[link-travis]: https://travis-ci.org/gofabian/negotiation-middleware
[link-coverage]: https://coveralls.io/github/gofabian/negotiation-middleware?branch=master

[ico-version]: https://img.shields.io/packagist/v/gofabian/negotiation-middleware.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/gofabian/negotiation-middleware/master.svg?style=flat-square
[ico-coverage]: https://coveralls.io/repos/gofabian/negotiation-middleware/badge.svg?branch=master&service=github
[ico-code-quality]: https://img.shields.io/scrutinizer/g/gofabian/negotiation-middleware.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/gofabian/negotiation-middleware.svg?style=flat-square


The **Negotiation Middleware** is a PHP library that negotiates accept headers of HTTP requests. The middleware chooses the most fitting options by looking at the accepted values of client and server. It supports the headers *accept*, *accept-language*, *accept-encoding* and *accept-charset*.

This library is a [middleware][] for the [Slim framework 3][slim] but may be used for any PHP code that uses HTTP messages conform to [PSR-7][psr7]. The Negotiation Middleware is based on the [library from William Durand][negotiation].

[middleware]: http://www.slimframework.com/docs/concepts/middleware.html "Concept of Slim middleware"
[slim]:         http://www.slimframework.com/               "Slim - a PHP micro framework"
[psr7]:        http://www.php-fig.org/psr/psr-7/             "PSR-7 - HTTP message interfaces"
[negotiation]:  https://github.com/willdurand/Negotiation   "Negotiation library by William Durand"

##Installation
The recommended way to install the Negotiation Middleware is using [Composer][composer]:

[composer]: https://getcomposer.org/  "Composer - Dependency Manager for PHP"

``` bash
$ composer require gofabian/negotiation-middleware
```

Composer fetches all dependencies automatically. The Negotiation Middleware uses common standards and tries to reduce the number of required packages follwing the [KISS principle][kiss]:

[kiss]: https://en.wikipedia.org/wiki/KISS_principle  "Keep it simple, stupid"

- PHP 5.4+
- PSR-7
- willdurand/negotiation 2.0.1
- recommended: slim/slim 3.x

## Usage
The first example describes how to use the Slim framework in combination with the Negotiation Middleware. The following examples are less detailed and bring specific aspects into focus.

### Slim and the Negotiation Middleware

The most common way is to negotiate the media type. In this example the server accepts the media types `text/html` and `application/json`:

``` php
<?php
use Gofabian\Negotiation\NegotiationMiddleware;

// create Slim app
$app = new \Slim\App;

// configure middleware
$app->add(new NegotiationMiddleware([
    'accept' => ['text/html', 'application/json']
]));

// use negotiated media type
$app->get('/mediatype', function ($request, $response, $args) {
    $negotiation = $request->getAttribute('negotiation');
    $mediaType = $negotiation->getMediaType();
    return $response->write("media type = " . $mediaType);
});

// run app
$app->run();
```
Let's have a look at incoming HTTP requests and the **priority of accepted media types**:

* If the accept header includes `text/html` the HTTP response will be `media type = text/html`. The first entry of the configured media types has the highest priority.

* If the accept header includes `application/json` but not `text/html` the HTTP response will be `media type = application/json`.

* If the accept header is empty the HTTP response will be `media type = text/html`. By default the entry with highest priority will be chosen if the accept header does not exist.

* If the accept header is available but includes neither `application/json` nor `text/html` the HTTP response will have status **406 "Not Acceptable"**.

### Accept headers

In addition to *media type* the middleware negotiates *language*, *charset* and *encoding*:

``` php
$app->add(new NegotiationMiddleware([
    'accept' => ['text/html', 'application/json'],
    'accept-language' => ['en', 'de-DE'],
    'accept-encoding' => ['gzip'],
    'accept-charset' => ['utf-8', 'ascii']
]));
```

### HTTP Status 406 - Not Acceptable

If the HTTP request contains an accept header but none of its values is accepted by the Negotiation Middleware the HTTP response will have the HTTP status 406 "Not Acceptable".

If the HTTP request does not contain an accept header the Negotiation Middleware will take the value with highest priority. Alternatively you can answer such requests with HTTP status 406, too:

```php
$app->add(new NegotiationMiddleware(
    [ 'accept-language' => ['en', 'de-DE'] ],
    false // 406 status for empty accept headers
));
```
In this example the Negotiation Middleware will return HTTP status 406 if the HTTP request does not contain the accept header `accept-language`.

### Negotiation result

The Negotiation Middleware puts the negotiation result into an instance of `AcceptProvider` and attaches it as an attribute to the PSR-7 HTTP request. The attribute name is `negotiation` by default and may be set like this:

```php
$app->add(new NegotiationMiddleware(
    [ 'accept' => ['text/xml'] ],
    true,
    'foo' // custom name
));

$app->get('/example', function($request, $response, $args) {
    $result = $request->getAttribute('foo');
    return $response->write($result->getMediaType());
});
```

The easy way to access the negotiation results is to use the attached `AcceptProvider` like this:

```php
$result = $request->getAttribute('negotiation');

$result->getMediaType();  // these
$result->getLanguage();   // getters
$result->getCharset();    // return
$result->getEncoding();   // strings
```

Alternatively you can get the original result objects of the [negotiation library][negotiation] like this:

```php
$result = $request->getAttribute('negotiation');

$result->getAccept();          // results
$result->getAcceptLanguage();  // from
$result->getAcceptCharset();   // negotiation
$result->getAcceptEncoding();  // library
```

Have a look at the [original documentation][negotiation-doc] for more information.

[negotiation-doc]: http://williamdurand.fr/Negotiation/#accept-classes "Documentation of the negotiation library by William Durand"

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- [gofabian][link-author]
- [All Contributors][link-contributors]

[link-author]: https://github.com/gofabian
[link-contributors]: ../../contributors

## License

The MIT License (MIT). Please see [License File][license] for more information.

[license]: LICENSE.md   "MIT License"
