<?php

namespace Lendable\GoCardlessEnterpise\Model;

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
     * @var CustomerBankAccount
     */
    protected $customerBankAccount;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var Creditor
     */
    protected $creditor;

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        if (array_key_exists('customerBankAccount', $arr)) {
            unset($arr['customerBankAccount']);
        }

        if ($this->getCustomerBankAccount() instanceof CustomerBankAccount) {
            $arr['links']['customer_bank_account'] = $this->getCustomerBankAccount()->getId();
        }

        if (array_key_exists('creditor', $arr)) {
            unset($arr['creditor']);
        }

        if ($this->getCreditor() instanceof Creditor) {
            $arr['links']['creditor'] = $this->getCreditor()->getId();
        }

        return $arr;
    }

    /**
     * @param CustomerBankAccount $bankAccount
     */
    public function setCustomerBankAccount(CustomerBankAccount $bankAccount)
    {
        $this->customerBankAccount = $bankAccount;
    }

    /**
     * @return CustomerBankAccount
     */
    public function getCustomerBankAccount()
    {
        return $this->customerBankAccount;
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

    /**
     * @param Creditor $creditor
     */
    public function setCreditor(Creditor $creditor)
    {
        $this->creditor = $creditor;
    }

    /**
     * @return Creditor
     */
    public function getCreditor()
    {
        return $this->creditor;
    }
}
