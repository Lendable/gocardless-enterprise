<?php

namespace Lendable\GoCardlessEnterprise\Model;

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

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setPayoutType(string $payoutType)
    {
        $this->payout_type = $payoutType;
    }

    public function getPayoutType(): string
    {
        return $this->payout_type;
    }

    public function setReference(string $reference)
    {
        $this->reference = $reference;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setDeductedFees(int $deductedFees)
    {
        $this->deducted_fees = $deductedFees;
    }

    public function getDeductedFees(): int
    {
        return $this->deducted_fees;
    }

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setArrivalDate(string $arrivalDate)
    {
        $this->arrival_date = $arrivalDate;
    }

    public function getArrivalDate(): string
    {
        return $this->arrival_date;
    }

    public function setCreditor(Creditor $creditor)
    {
        $this->creditor = $creditor;
    }

    public function getCreditor(): Creditor
    {
        return $this->creditor;
    }

    public function setCreditorBankAccount(CreditorBankAccount $creditorBankAccount)
    {
        $this->creditor_bank_account = $creditorBankAccount;
    }

    public function getCreditorBankAccount(): CreditorBankAccount
    {
        return $this->creditor_bank_account;
    }

    public function toArray(): array
    {
        $arr = parent::toArray();

        if (\array_key_exists('creditor', $arr)) {
            unset($arr['creditor']);
        }

        if ($this->getCreditor() instanceof Creditor) {
            $arr['links']['creditor'] = $this->getCreditor()->getId();
        }

        if (\array_key_exists('creditor_bank_account', $arr)) {
            unset($arr['creditor_bank_account']);
        }

        if ($this->getCreditorBankAccount() instanceof CreditorBankAccount) {
            $arr['links']['creditor_bank_account'] = $this->getCreditorBankAccount()->getId();
        }

        return $arr;
    }
}
