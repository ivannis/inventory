<?php

declare(strict_types=1);

namespace Stock\Infrastructure\Http\Handler;

use Commanded\Core\Exception\Exception;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class HttpExceptionHandler extends ExceptionHandler
{
    private StdoutLoggerInterface $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf(
            '%s[%s] in %s',
            $throwable->getMessage(),
            $throwable->getLine(),
            $throwable->getFile()
        ));

        $this->logger->error($throwable->getTraceAsString());

        if ($throwable instanceof ValidationException) {
            $body = $this->handleValidationException($throwable);
        } elseif ($throwable instanceof Exception) {
            $body = $body = [
                'code' => $throwable->getCode(),
                'message' => $throwable->getMessage(),
                'reason' => $throwable->getReason(),
            ];
        } else {
            $body = [
                'code' => $throwable->getCode() !== 0 ? $throwable->getCode() : 500,
                'message' => 'Internal Server Error.',
                'reason' => 'UNKNOWN_ERROR',
            ];
        }

        return $response
            ->withStatus($body['code'])
            ->withHeader('content-type', 'application/json')
            ->withBody(new SwooleStream(json_encode($body)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }

    private function handleValidationException(ValidationException $throwable): array
    {
        $errors = [];
        foreach ($throwable->validator->errors()->keys() as $key) {
            if ($throwable->validator->errors()->has($key)) {
                $errors[$key] = $throwable->validator->errors()->first($key);
            }
        }

        return [
            'code' => 422,
            'message' => 'Bad request',
            'reason' => 'UNPROCESSABLE_ENTITY',
            'errors' => $errors,
        ];
    }
}
