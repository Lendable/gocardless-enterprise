<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 14/08/14
 * Time: 15:51
 */

namespace GoCardless\Enterprise\Model;


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
     * @param string $account_holder_name
     */
    public function setAccountHolderName($account_holder_name)
    {
        $this->account_holder_name = $account_holder_name;
    }

    /**
     * @return string
     */
    public function getAccountHolderName()
    {
        return $this->account_holder_name;
    }

    /**
     * @param string $account_number
     */
    public function setAccountNumber($account_number)
    {
        $this->account_number = $account_number;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * @param string $account_number_ending
     */
    public function setAccountNumberEnding($account_number_ending)
    {
        $this->account_number_ending = $account_number_ending;
    }

    /**
     * @return string
     */
    public function getAccountNumberEnding()
    {
        return $this->account_number_ending;
    }

    /**
     * @param string $bank_name
     */
    public function setBankName($bank_name)
    {
        $this->bank_name = $bank_name;
    }

    /**
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
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
     * @param string $sort_code
     */
    public function setSortCode($sort_code)
    {
        $this->sort_code = $sort_code;
    }

    /**
     * @return string
     */
    public function getSortCode()
    {
        return $this->sort_code;
    }
    
    /**
     * @param string $bank_code
     */    
        public function setBankCode($bank_code)
    {
        $this->bank_code = $bank_code;
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
