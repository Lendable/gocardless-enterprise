<?php

namespace Lendable\GoCardlessEnterpise\Exceptions;

use GuzzleHttp\Exception\BadResponseException;

class ApiException extends BadResponseException
{
    /**
     * @param BadResponseException $old
     * @return self
     */
    public static function fromBadResponseException(BadResponseException $old)
    {
        return new self($old->getMessage(), $old->getRequest(), $old->getResponse());
    }

    /**
     * @return string
     */
    public function getReasonPhrase()
    {
        return (string) $this->getResponse()->getBody();
    }
}
