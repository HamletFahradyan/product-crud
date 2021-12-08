<?php
declare(strict_types=1);

namespace App\Classes\Dto;


use Spatie\DataTransferObject\DataTransferObject;

class ProductDto extends DataTransferObject
{
    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    public int $user_id;
}
