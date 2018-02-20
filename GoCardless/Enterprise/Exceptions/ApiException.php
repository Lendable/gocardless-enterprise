<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 12/08/14
 * Time: 13:38
 */

namespace GoCardless\Enterprise\Exceptions;

use GuzzleHttp\Exception\BadResponseException;

class ApiException extends BadResponseException
{
    public static function fromBadResponseException(BadResponseException $old)
    {
        return new self($old->getMessage(), $old->getRequest(), $old->getResponse());
    }

    public function getReasonPhrase()
    {
        return (string) $this->getResponse()->getBody();
    }
}