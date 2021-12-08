<?php
declare(strict_types=1);

namespace App\Classes\Exceptions;


use Illuminate\Http\Response;

class ProductNotFoundException extends JsonException
{
    /**
     * @var int|null
     */
    protected ?int $statusCode = Response::HTTP_NOT_FOUND;

    /**
     * @var string
     */
    protected $message = 'This product not found';
}

