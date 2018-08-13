<?php

namespace Lendable\GoCardlessEnterpise\Model;

class Payment extends Model
{
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
    protected $description;

    /**
     * @var string
     */
    protected $charge_date;

    /**
     * @var int
     */
    protected $transaction_fee;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var Mandate
     */
    protected $mandate;

    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * @param int $amount Amount must be in whole pence/cents
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int Amount is in whole pence/cents
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param Mandate $mandate
     */
    public function setMandate(Mandate $mandate)
    {
        $this->mandate = $mandate;
    }

    /**
     * @return Mandate
     */
    public function getMandate()
    {
        return $this->mandate;
    }

    /**
     * @param string $chargeDate
     */
    public function setCollectedAt($chargeDate)
    {
        $this->charge_date = $chargeDate;
    }

    /**
     * @return string
     */
    public function getChargeDate()
    {
        return $this->charge_date;
    }

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
     * @param int $transactionFee Amount must be in whole pence/cents
     */
    public function setTransactionFee($transactionFee)
    {
        $this->transaction_fee = $transactionFee;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function addMetadata($key, $value)
    {
        $this->metadata[$key] = (string) $value;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata(array $metadata)
    {
        foreach ($metadata as $k => $v) {
            $metadata[$k] = (string) $v;
        }
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @return int Amount is in whole pence/cents
     */
    public function getTransactionFee()
    {
        return $this->transaction_fee;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $arr = parent::toArray();

        if (array_key_exists('mandate', $arr)) {
            unset($arr['mandate']);
        }

        if ($this->getMandate() instanceof Mandate) {
            $arr['links']['mandate'] = $this->getMandate()->getId();
        }

        return $arr;
    }
}
