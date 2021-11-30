<?php

namespace Lendable\GoCardlessEnterprise\Model;

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

    public function toArray(): array
    {
        $arr = parent::toArray();

        if (\array_key_exists('customerBankAccount', $arr)) {
            unset($arr['customerBankAccount']);
        }

        if ($this->getCustomerBankAccount() instanceof CustomerBankAccount) {
            $arr['links']['customer_bank_account'] = $this->getCustomerBankAccount()->getId();
        }

        if (\array_key_exists('creditor', $arr)) {
            unset($arr['creditor']);
        }

        if ($this->getCreditor() instanceof Creditor) {
            $arr['links']['creditor'] = $this->getCreditor()->getId();
        }

        return $arr;
    }

    public function setCustomerBankAccount(CustomerBankAccount $bankAccount)
    {
        $this->customerBankAccount = $bankAccount;
    }

    public function getCustomerBankAccount(): CustomerBankAccount
    {
        return $this->customerBankAccount;
    }

    public function setReference(string $reference)
    {
        $this->reference = $reference;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setScheme(string $scheme)
    {
        $this->scheme = $scheme;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setCreditor(Creditor $creditor)
    {
        $this->creditor = $creditor;
    }

    public function getCreditor(): Creditor
    {
        return $this->creditor;
    }
}
