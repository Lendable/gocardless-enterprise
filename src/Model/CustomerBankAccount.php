<?php

namespace Lendable\GoCardlessEnterprise\Model;

class CustomerBankAccount extends Model
{
    /**
     * @var string
     */
    protected $account_number;

    /**
     * @var string
     */
    protected $branch_code;

    /**
     * @var string
     */
    protected $country_code;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $account_holder_name;

    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Mandate[]
     */
    protected $mandates;

    public function toArray(): array
    {
        $arr = parent::toArray();

        if (\array_key_exists('customer', $arr)) {
            unset($arr['customer']);
        }

        if ($this->getCustomer() instanceof Customer) {
            $arr['links']['customer'] = $this->getCustomer()->getId();
        }

        if (\array_key_exists('mandates', $arr)) {
            unset($arr['mandates']);
        }

        return $arr;
    }

    public function setAccountHolderName(string $accountHolderName)
    {
        $this->account_holder_name = $accountHolderName;
    }

    public function getAccountHolderName(): string
    {
        return $this->account_holder_name;
    }

    public function setAccountNumber(string $accountNumber)
    {
        $this->account_number = $accountNumber;
    }

    public function getAccountNumber(): string
    {
        return $this->account_number;
    }

    public function setCountryCode(string $countryCode)
    {
        $this->country_code = $countryCode;
    }

    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setBranchCode(string $branchCode)
    {
        $this->branch_code = $branchCode;
    }

    public function getBranchCode(): string
    {
        return $this->branch_code;
    }

    /**
     * @param Mandate[] $mandates
     */
    public function setMandates(array $mandates)
    {
        $this->mandates = $mandates;
    }

    /**
     * @return Mandate[]
     */
    public function getMandates(): array
    {
        return $this->mandates;
    }
}
