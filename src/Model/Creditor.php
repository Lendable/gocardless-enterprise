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
     * @param string $address_line1
     */
    public function setAddressLine1($address_line1)
    {
        $this->address_line1 = $address_line1;
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->address_line1;
    }

    /**
     * @param string $address_line2
     */
    public function setAddressLine2($address_line2)
    {
        $this->address_line2 = $address_line2;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->address_line2;
    }

    /**
     * @param string $address_line3
     */
    public function setAddressLine3($address_line3)
    {
        $this->address_line3 = $address_line3;
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
     * @param string $country_code
     */
    public function setCountryCode($country_code)
    {
        $this->country_code = $country_code;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }

    /**
     * @param \GoCardless\Enterprise\Model\CreditorBankAccount $eur_payout_account
     */
    public function setEurPayoutAccount($eur_payout_account)
    {
        $this->eur_payout_account = $eur_payout_account;
    }

    /**
     * @return \GoCardless\Enterprise\Model\CreditorBankAccount
     */
    public function getEurPayoutAccount()
    {
        return $this->eur_payout_account;
    }

    /**
     * @param \GoCardless\Enterprise\Model\CreditorBankAccount $gbp_payout_account
     */
    public function setGbpPayoutAccount($gbp_payout_account)
    {
        $this->gbp_payout_account = $gbp_payout_account;
    }

    /**
     * @return \GoCardless\Enterprise\Model\CreditorBankAccount
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
     * @param string $postal_code
     */
    public function setPostalCode($postal_code)
    {
        $this->postal_code = $postal_code;
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
