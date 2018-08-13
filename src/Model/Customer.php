<?php

namespace Lendable\GoCardlessEnterpise\Model;

class Customer extends Model
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $given_name;

    /**
     * @var string
     */
    protected $family_name;

    /**
     * @var string
     */
    protected $organisation_name;

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
     * @var array
     */
    protected $bankAccounts;

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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $family_name
     */
    public function setFamilyName($family_name)
    {
        $this->family_name = $family_name;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
        return $this->family_name;
    }

    /**
     * @param string $given_name
     */
    public function setGivenName($given_name)
    {
        $this->given_name = $given_name;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
        return $this->given_name;
    }

    /**
     * @param string $organisation_name
     */
    public function setOrganisationName($organisation_name)
    {
        $this->organisation_name = $organisation_name;
    }

    /**
     * @return string
     */
    public function getOrganisationName()
    {
        return $this->organisation_name;
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

    /**
     * @param array $bankAccounts
     */
    public function setBankAccounts($bankAccounts)
    {
        $this->bankAccounts = $bankAccounts;
    }

    /**
     * @return array
     */
    public function getBankAccounts()
    {
        return $this->bankAccounts;
    }

    public function toArray()
    {
        $arr = parent::toArray();
        if (array_key_exists('bankAccounts', $arr)) {
            unset($arr['bankAccounts']);
        }

        return $arr;
    }
}
