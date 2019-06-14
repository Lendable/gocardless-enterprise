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

    /**
     * @param string $addressLine1
     */
    public function setAddressLine1($addressLine1)
    {
        $this->address_line1 = $addressLine1;
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->address_line1;
    }

    /**
     * @param string $addressLine2
     */
    public function setAddressLine2($addressLine2)
    {
        $this->address_line2 = $addressLine2;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->address_line2;
    }

    /**
     * @param string $addressLine3
     */
    public function setAddressLine3($addressLine3)
    {
        $this->address_line3 = $addressLine3;
    }

    /**
     * @return string
     */
    public function getAddressLine3()
    {
        return $this->address_line3;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
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
     * @param CreditorBankAccount $eurPayoutAccount
     */
    public function setEurPayoutAccount(CreditorBankAccount $eurPayoutAccount)
    {
        $this->eur_payout_account = $eurPayoutAccount;
    }

    /**
     * @return CreditorBankAccount
     */
    public function getEurPayoutAccount()
    {
        return $this->eur_payout_account;
    }

    /**
     * @param CreditorBankAccount $gbpPayoutAccount
     */
    public function setGbpPayoutAccount(CreditorBankAccount $gbpPayoutAccount)
    {
        $this->gbp_payout_account = $gbpPayoutAccount;
    }

    /**
     * @return CreditorBankAccount
     */
    public function getGbpPayoutAccount()
    {
        return $this->gbp_payout_account;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postal_code = $postalCode;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }
}
