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

    public function setAccountNumberEnding(string $accountNumberEnding)
    {
        $this->account_number_ending = $accountNumberEnding;
    }

    public function getAccountNumberEnding(): string
    {
        return $this->account_number_ending;
    }

    public function setBankName(string $bankName)
    {
        $this->bank_name = $bankName;
    }

    public function getBankName(): string
    {
        return $this->bank_name;
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

    public function setSortCode(string $sortCode)
    {
        $this->sort_code = $sortCode;
    }

    public function getSortCode(): string
    {
        return $this->sort_code;
    }

    public function setBankCode(string $bankCode)
    {
        $this->bank_code = $bankCode;
    }

    public function getBankCode(): string
    {
        return $this->bank_code;
    }

    public function setIban(string $iban)
    {
        $this->iban = $iban;
    }

    public function getIban(): string
    {
        return $this->iban;
    }
}
