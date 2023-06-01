<?php

namespace App\Exceptions;

use Throwable;

class BadRequestException extends ApiException
{
    /**
     * BadRequestException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param array $headers
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null, array $headers = [])
    {
        parent::__construct(400, $message, $previous, $headers, $code);
    }
}
