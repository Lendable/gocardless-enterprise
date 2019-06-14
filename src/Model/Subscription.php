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
     * @param string $intervalUnit
     */
    public function setIntervalUnit($intervalUnit)
    {
        $this->interval_unit = $intervalUnit;
    }

    /**
     * @return string
     */
    public function getIntervalUnit()
    {
        return $this->interval_unit;
    }

    /**
     * @param int $dayOfMonth
     */
    public function setDayOfMonth($dayOfMonth)
    {
        $this->day_of_month = $dayOfMonth;
    }

    /**
     * @return int
     */
    public function getDayOfMonth()
    {
        return $this->day_of_month;
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
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param string $key
     * @param mixed $value Must be stringifiable
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
