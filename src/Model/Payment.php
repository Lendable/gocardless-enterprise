<?php

namespace Lendable\GoCardlessEnterprise\Model;

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
     * @var string
     */
    protected $reference;

    /**
     * @param int $amount Amount must be in whole pence/cents
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int Amount is in whole pence/cents
     */
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

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setMandate(Mandate $mandate)
    {
        $this->mandate = $mandate;
    }

    public function getMandate(): Mandate
    {
        return $this->mandate;
    }

    public function setCollectedAt(string $chargeDate)
    {
        $this->charge_date = $chargeDate;
    }

    public function getChargeDate(): string
    {
        return $this->charge_date;
    }

    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param int $transactionFee Amount must be in whole pence/cents
     */
    public function setTransactionFee(int $transactionFee)
    {
        $this->transaction_fee = $transactionFee;
    }

    /**
     * @param float|int|object|string|null $value Must be stringifiable
     */
    public function addMetadata(string $key, $value)
    {
        $this->metadata[$key] = (string) $value;
    }

    public function setMetadata(array $metadata)
    {
        foreach ($metadata as $k => $v) {
            $metadata[$k] = (string) $v;
        }
        $this->metadata = $metadata;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return int Amount is in whole pence/cents
     */
    public function getTransactionFee(): int
    {
        return $this->transaction_fee;
    }

    public function toArray(): array
    {
        $arr = parent::toArray();

        if (\array_key_exists('mandate', $arr)) {
            unset($arr['mandate']);
        }

        if ($this->getMandate() instanceof Mandate) {
            $arr['links']['mandate'] = $this->getMandate()->getId();
        }

        return $arr;
    }
}
