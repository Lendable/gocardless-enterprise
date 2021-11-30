<?php

namespace Lendable\GoCardlessEnterprise\Model;

class Creditor extends Model
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $address_line1;

    /**
     * @var string
     */
    protected $address_line2;

    /**
     * @var string
     */
    protected $address_line3;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $region;

    /**
     * @var string
     */
    protected $postal_code;

    /**
     * @var string
     */
    protected $country_code;

    /**
     * @var CreditorBankAccount
     */
    protected $gbp_payout_account;

    /**
     * @var CreditorBankAccount
     */
    protected $eur_payout_account;

    public function setAddressLine1(string $addressLine1)
    {
        $this->address_line1 = $addressLine1;
    }

    public function getAddressLine1(): string
    {
        return $this->address_line1;
    }

    public function setAddressLine2(string $addressLine2)
    {
        $this->address_line2 = $addressLine2;
    }

    public function getAddressLine2(): string
    {
        return $this->address_line2;
    }

    public function setAddressLine3(string $addressLine3)
    {
        $this->address_line3 = $addressLine3;
    }

    public function getAddressLine3(): string
    {
        return $this->address_line3;
    }

    public function setCity(string $city)
    {
        $this->city = $city;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCountryCode(string $countryCode)
    {
        $this->country_code = $countryCode;
    }

    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    public function setEurPayoutAccount(CreditorBankAccount $eurPayoutAccount)
    {
        $this->eur_payout_account = $eurPayoutAccount;
    }

    public function getEurPayoutAccount(): CreditorBankAccount
    {
        return $this->eur_payout_account;
    }

    public function setGbpPayoutAccount(CreditorBankAccount $gbpPayoutAccount)
    {
        $this->gbp_payout_account = $gbpPayoutAccount;
    }

    public function getGbpPayoutAccount(): CreditorBankAccount
    {
        return $this->gbp_payout_account;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPostalCode(string $postalCode)
    {
        $this->postal_code = $postalCode;
    }

    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    public function setRegion(string $region)
    {
        $this->region = $region;
    }

    public function getRegion(): string
    {
        return $this->region;
    }
}
