<?php

declare(strict_types=1);

namespace Lendable\GoCardlessEnterprise\Exceptions;

use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

class ApiException extends BadResponseException
{
    public static function fromBadResponseException(BadResponseException $old): self
    {
        return new self($old->getMessage(), $old->getRequest(), $old->getResponse());
    }

    public function getReasonPhrase(): ?string
    {
        $response = $this->getResponse();
        if (!$response instanceof ResponseInterface) {
            return null;
        }

        return (string) $response->getBody();
    }
}
