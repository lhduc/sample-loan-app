<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\ArrayShape;
use ReturnTypeWillChange;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiException extends HttpException implements Throwable
{
    /**
     * @var int
     */
    protected int $statusCode;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * ApiException constructor.
     *
     * @param int $statusCode
     * @param ?string $message
     * @param Throwable|null $previous
     * @param array $headers
     * @param ?int $code
     */
    public function __construct(int $statusCode, ?string $message = '', Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @param array $data
     */
    public function setData(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array[]
     */
    #[ArrayShape(['error' => "array"])]
    public function getResponseData(): array
    {
        return [
            'error' => [
                'code' => $this->code,
                'message' => $this->message,
                'data' => $this->data,
            ]
        ];
    }

    /**
     * @return false|string
     */
    #[ReturnTypeWillChange] public function __toString()
    {
        return json_encode($this->getResponseData());
    }
}
