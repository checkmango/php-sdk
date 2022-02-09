<?php

namespace Checkmango\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Checkmango\Exception\ApiLimitExceededException;
use Checkmango\Exception\ClientException;
use Checkmango\Exception\EndpointNotFoundException;
use Checkmango\Exception\ExceptionInterface;
use Checkmango\Exception\ResourceNotFoundException;
use Checkmango\Exception\RuntimeException;
use Checkmango\Exception\ServerException;
use Checkmango\Exception\ValidationFailedException;
use Checkmango\HttpClient\Message\ResponseMediator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ExceptionThrower implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $next($request)->then(function (ResponseInterface $response): ResponseInterface {
            $status = $response->getStatusCode();
            if ($status >= 400 && $status < 600) {
                throw self::createException($status, ResponseMediator::getErrorMessage($response) ?? $response->getReasonPhrase());
            }

            return $response;
        });
    }

    private static function createException(int $status, string $message, array $errors = []): ExceptionInterface
    {
        if (404 === $status) {
            if ($message === 'Not Found') {
                return new EndpointNotFoundException($message, $status);
            }

            if ($message === 'Resource Not Found') {
                return new ResourceNotFoundException($message, $status);
            }
        }

        if (400 === $status || 422 === $status) {
            return (new ValidationFailedException($message, $status))->withErrors($errors);
        }

        if (429 === $status) {
            return new ApiLimitExceededException($message, $status);
        }

        if (500 <= $status) {
            return new ServerException($message, $status);
        }

        if (400 <= $status) {
            return new ClientException($message, $status);
        }

        return new RuntimeException($message, $status);
    }
}
