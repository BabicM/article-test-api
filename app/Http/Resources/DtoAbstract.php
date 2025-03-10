<?php declare(strict_types=1);

namespace App\Http\Resources;

abstract class DtoAbstract
{
    public function toArray(): array
    {
        return (array) $this;
    }

}
