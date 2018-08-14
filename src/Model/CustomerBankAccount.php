<?php

namespace Lendable\GoCardlessEnterpise\Model;

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

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        if (array_key_exists('customer', $arr)) {
            unset($arr['customer']);
        }

        if ($this->getCustomer() instanceof Customer) {
            $arr['links']['customer'] = $this->getCustomer()->getId();
        }

        if (array_key_exists('mandates', $arr)) {
            unset($arr['mandates']);
        }


        return $arr;
    }

    /**
     * @param string $accountHolderName
     */
    public function setAccountHolderName($accountHolderName)
    {
        $this->account_holder_name = $accountHolderName;
    }

    /**
     * @return string
     */
    public function getAccountHolderName()
    {
        return $this->account_holder_name;
    }

    /**
     * @param string $accountNumber
     */
    public function setAccountNumber($accountNumber)
    {
        $this->account_number = $accountNumber;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->country_code = $countryCode;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param string $branchCode
     */
    public function setBranchCode($branchCode)
    {
        $this->branch_code = $branchCode;
    }

    /**
     * @return string
     */
    public function getBranchCode()
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
    public function getMandates()
    {
        return $this->mandates;
    }
}
