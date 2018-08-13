<?php

namespace Lendable\GoCardlessEnterpise\Exceptions;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

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
     * @return string|null
     */
    public function getReasonPhrase()
    {
        $response = $this->getResponse();
        if (!$response instanceof ResponseInterface) {
            return null;
        }

        return (string) $response->getBody();
    }
}
