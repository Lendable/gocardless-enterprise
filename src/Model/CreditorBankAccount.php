<?php

namespace Lendable\GoCardlessEnterprise\Model;

class CreditorBankAccount extends Model
{
    /**
     * @var string
     */
    protected $account_holder_name;

    /**
     * @var string
     */
    protected $account_number;

    /**
     * @var string
     */
    protected $sort_code;

    /**
     * @var string
     */
    protected $account_number_ending;

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
    protected $bank_name;

    /**
     * @var string
     */
    protected $bank_code;

    /**
     * @var string
     */
    protected $iban;

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
     * @param string $accountNumberEnding
     */
    public function setAccountNumberEnding($accountNumberEnding)
    {
        $this->account_number_ending = $accountNumberEnding;
    }

    /**
     * @return string
     */
    public function getAccountNumberEnding()
    {
        return $this->account_number_ending;
    }

    /**
     * @param string $bankName
     */
    public function setBankName($bankName)
    {
        $this->bank_name = $bankName;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
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
     * @param string $sortCode
     */
    public function setSortCode($sortCode)
    {
        $this->sort_code = $sortCode;
    }

    /**
     * @return string
     */
    public function getSortCode()
    {
        return $this->sort_code;
    }

    /**
     * @param string $bankCode
     */
    public function setBankCode($bankCode)
    {
        $this->bank_code = $bankCode;
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bank_code;
    }

    /**
     * @param string $iban
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
    }

    /**
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }
}
