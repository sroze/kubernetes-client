# Kubernetes client

[![Build Status](https://travis-ci.org/sroze/kubernetes-client.svg?branch=master)](https://travis-ci.org/sroze/kubernetes-client)

A library that provide a client for the Kubernetes API client.

## Getting started

To create an anonymous client, you can:

```php
$httpClient = new GuzzleHttpClient(
    new Client(),
    'baseUrl',
    'version'
);

$client = new Client(
    new HttpAdapter($httpClient, new Serializer())
);
```

To had user authentication, you can decorate the http client:
```php
$authenticatedHttpClient = new AuthenticationMiddleware(
    $httpClient,
    'username',
    'password'
);
```

## Development

Install application dependencies:

```
composer install
```

## Tests

Tests are specifications written with [PhpSpec](http://github.com/phpspec/phpspec).

```
./bin/phpspec run
```
