<?php

namespace Prove\HttpClient\Plugin;

use Http\Promise\Promise;
use Http\Client\Common\Plugin;
use Prove\Exception\ClientException;
use Prove\Exception\ServerException;
use Prove\Exception\RuntimeException;
use Psr\Http\Message\RequestInterface;
use Prove\Exception\ExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Prove\Exception\ApiLimitExceededException;
use Prove\Exception\EndpointNotFoundException;
use Prove\Exception\ResourceNotFoundException;
use Prove\Exception\ValidationFailedException;
use Prove\HttpClient\Message\ResponseMediator;

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

    private static function createException(int $status, string $message): ExceptionInterface
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
            return new ValidationFailedException($message, $status);
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
