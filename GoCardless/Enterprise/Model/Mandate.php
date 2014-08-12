<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 17:36
 */

namespace GoCardless\Enterprise\Model;


class Mandate extends Model
{
    /**
     * @var string
     */
    protected $reference;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var BankAccount
     */
    protected $bankAccount;

    /**
     * @var string
     */
    protected $status;

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        if(array_key_exists("bankAccount", $arr)){
            unset($arr["bankAccount"]);
        }

        if($this->getBankAccount() instanceof BankAccount)
        {
            $arr["links"]["customer_bank_account"] = $this->getBankAccount()->getId();
        }

        return $arr;
    }

    /**
     * @param BankAccount $bankAccount
     */
    public function setBankAccount(BankAccount $bankAccount)
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return BankAccount
     */
    public function getBankAccount()
    {
        return $this->bankAccount;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param string $scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
} 