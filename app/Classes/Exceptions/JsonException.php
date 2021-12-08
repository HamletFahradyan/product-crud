<?php
declare(strict_types=1);

namespace App\Classes\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonException extends HttpException
{
    /**
     * @var int|null
     */
    protected ?int $statusCode = Response::HTTP_BAD_REQUEST;

    /**
     * @var string
     */
    protected $message = 'Bad request';
    /**
     * @param int|null $statusCode
     * @param string|null $message
     * @param \Throwable|null $previous
     * @param array $headers
     * @param int|null $code
     */
    public function __construct(?int $statusCode = null, ?string $message = null, \Throwable $previous = null, array $headers = [], ?int $code = 0)
    {
        if ($statusCode) {
            $this->statusCode = $statusCode;
        }

        if ($message) {
            $this->message = $message;
        }

        parent::__construct($this->statusCode, $this->message, $previous, $headers, $code);
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'status' => 'failed',
            'error'  => [
                'message' => $this->getMessage()
            ]
        ], $this->getStatusCode(), $this->getHeaders());
    }
}

