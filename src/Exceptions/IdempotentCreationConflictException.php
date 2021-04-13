<?php

namespace Lendable\GoCardlessEnterprise\Exceptions;

use GuzzleHttp\Exception\BadResponseException;

class IdempotentCreationConflictException extends ApiException
{
    /**
     * @param BadResponseException $old
     * @return self
     */
    public static function fromBadResponseException(BadResponseException $old)
    {
        return new self($old->getMessage(), $old->getRequest(), $old->getResponse());
    }
}
