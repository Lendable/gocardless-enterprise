<?php

namespace GoCardless\Enterprise\Model;

class Payout extends Model
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $payout_type;

    /**
     * @var string
     */
    protected $reference;

    /**
     * @var int
     */
    protected $deducted_fees;

    /**
     * @var int
     */
    protected $amount;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var string
     */
    protected $arrival_date;

    /**
     * @var Creditor
     */
    protected $creditor;

    /**
     * @var CreditorBankAccount
     */
    protected $creditor_bank_account;

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $type
     */
    public function setPayoutType($payoutType)
    {
        $this->payout_type = $payoutType;
    }

    /**
     * @return string
     */
    public function getPayoutType()
    {
        return $this->payout_type;
    }

    /**
     * @param string $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param int $deductedFees
     */
    public function setDeductedFees($deductedFees)
    {
        $this->deducted_fees = $deductedFees;
    }

    /**
     * @return int
     */
    public function getDeductedFees()
    {
        return $this->deducted_fees;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
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
     * @param string $arrivalDate
     */
    public function setArrivalDate($arrivalDate)
    {
        $this->arrival_date = $arrivalDate;
    }

    /**
     * @return string
     */
    public function getArrivalDate()
    {
        return $this->arrival_date;
    }

    /**
     * @param \GoCardless\Enterprise\Model\Creditor $creditor
     */
    public function setCreditor($creditor)
    {
        $this->creditor = $creditor;
    }

    /**
     * @return \GoCardless\Enterprise\Model\Creditor
     */
    public function getCreditor()
    {
        return $this->creditor;
    }

    /**
     * @param \GoCardless\Enterprise\Model\CreditorBankAccount $creditorBankAccount
     */
    public function setCreditorBankAccount($creditorBankAccount)
    {
        $this->creditor_bank_account = $creditorBankAccount;
    }

    /**
     * @return \GoCardless\Enterprise\Model\CreditorBankAccount
     */
    public function getCreditorBankAccount()
    {
        return $this->creditor_bank_account;
    }

    public function toArray()
    {
        $arr = parent::toArray();

        if (array_key_exists('creditor', $arr)) {
            unset($arr['creditor']);
        }

        if ($this->getCreditor()) {
            $arr['links']['creditor'] = $this->getCreditor()->getId();
        }

        if (array_key_exists('creditor_bank_account', $arr)) {
            unset($arr['creditor_bank_account']);
        }

        if ($this->getCreditorBankAccount()) {
            $arr['links']['creditor_bank_account'] = $this->getCreditorBankAccount()->getId();
        }

        return $arr;
    }
}
