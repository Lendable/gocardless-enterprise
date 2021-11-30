<?php

declare(strict_types=1);

namespace Lendable\GoCardlessEnterprise\Exceptions;

use GuzzleHttp\Exception\BadResponseException;

class IdempotentCreationConflictException extends ApiException
{
    public static function fromBadResponseException(BadResponseException $old): self
    {
        return new self($old->getMessage(), $old->getRequest(), $old->getResponse());
    }
}
