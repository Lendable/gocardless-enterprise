<?php

namespace Lendable\GoCardlessEnterprise\Model;

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

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setFamilyName(string $familyName)
    {
        $this->family_name = $familyName;
    }

    public function getFamilyName(): string
    {
        return $this->family_name;
    }

    public function setGivenName(string $givenName)
    {
        $this->given_name = $givenName;
    }

    public function getGivenName(): string
    {
        return $this->given_name;
    }

    public function setOrganisationName(string $organisationName)
    {
        $this->organisation_name = $organisationName;
    }

    public function getOrganisationName(): string
    {
        return $this->organisation_name;
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

    public function setBankAccounts(array $bankAccounts)
    {
        $this->bankAccounts = $bankAccounts;
    }

    public function getBankAccounts(): array
    {
        return $this->bankAccounts;
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        if (\array_key_exists('bankAccounts', $arr)) {
            unset($arr['bankAccounts']);
        }

        return $arr;
    }
}
