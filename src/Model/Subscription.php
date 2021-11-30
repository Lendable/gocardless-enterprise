<?php

namespace Lendable\GoCardlessEnterprise\Model;

class Subscription extends Model
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
    protected $name;

    /**
     * @var string
     */
    protected $interval_unit;

    /**
     * @var int
     */
    protected $day_of_month;

    /**
     * @var Mandate
     */
    protected $mandate;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var array
     */
    protected $metadata = [];

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

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setIntervalUnit(string $intervalUnit)
    {
        $this->interval_unit = $intervalUnit;
    }

    public function getIntervalUnit(): string
    {
        return $this->interval_unit;
    }

    public function setDayOfMonth(int $dayOfMonth)
    {
        $this->day_of_month = $dayOfMonth;
    }

    public function getDayOfMonth(): int
    {
        return $this->day_of_month;
    }

    public function setMandate(Mandate $mandate)
    {
        $this->mandate = $mandate;
    }

    public function getMandate(): Mandate
    {
        return $this->mandate;
    }

    public function setCount(int $count)
    {
        $this->count = $count;
    }

    public function getCount(): int
    {
        return $this->count;
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
