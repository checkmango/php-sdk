<?php

namespace Checkmango\HttpClient\Plugin;

use Checkmango\Exception\ApiLimitExceededException;
use Checkmango\Exception\ClientException;
use Checkmango\Exception\EndpointNotFoundException;
use Checkmango\Exception\ExceptionInterface;
use Checkmango\Exception\ResourceNotFoundException;
use Checkmango\Exception\RuntimeException;
use Checkmango\Exception\ServerException;
use Checkmango\Exception\ValidationFailedException;
use Checkmango\HttpClient\Message\ResponseMediator;
use Http\Client\Common\Plugin;
use Http\Promise\Promise;
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
        if ($status === 404) {
            if ($message === 'Not Found') {
                return new EndpointNotFoundException($message, $status);
            }

            if ($message === 'Resource Not Found') {
                return new ResourceNotFoundException($message, $status);
            }
        }

        if ($status === 400 || $status === 422) {
            return (new ValidationFailedException($message, $status))->withErrors($errors);
        }

        if ($status === 429) {
            return new ApiLimitExceededException($message, $status);
        }

        if ($status >= 500) {
            return new ServerException($message, $status);
        }

        if ($status >= 400) {
            return new ClientException($message, $status);
        }

        return new RuntimeException($message, $status);
    }
}
