<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 12/08/14
 * Time: 13:38
 */

namespace GoCardless\Enterprise\Exceptions;


use Guzzle\Http\Exception\BadResponseException;

class ApiException extends BadResponseException
{
    public static function fromBadResponseException(BadResponseException $old)
    {
        $new = new self($old->getMessage());
        $new->setRequest($old->getRequest());
        $new->setResponse($old->getResponse());

        return $new;
    }

    public function getReasonPhrase()
    {
        return $this->getResponse()->getBody(true);
    }
} 