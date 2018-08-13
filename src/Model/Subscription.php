<?php

namespace Lendable\GoCardlessEnterpise\Model;

class Subscription extends Model
{
    protected $amount;

    protected $currency;

    protected $name;

    protected $interval_unit;

    protected $day_of_month;

    protected $mandate;

    protected $count;

    protected $metadata = [];

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setIntervalUnit($interval_unit)
    {
        $this->interval_unit = $interval_unit;
    }

    public function getIntervalUnit()
    {
        return $this->interval_unit;
    }

    public function setDayOfMonth($day_of_month)
    {
        $this->day_of_month = $day_of_month;
    }

    public function getDayOfMonth()
    {
        return $this->day_of_month;
    }

    public function setMandate($mandate)
    {
        $this->mandate = $mandate;
    }

    public function getMandate()
    {
        return $this->mandate;
    }

    public function setCount($count)
    {
        $this->count = $count;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function addMetadata($key, $value)
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

    public function getMetadata()
    {
        return $this->metadata;
    }

    public function toArray()
    {
        $arr = parent::toArray();

        if (array_key_exists('mandate', $arr)) {
            unset($arr['mandate']);
        }

        if ($this->getMandate()) {
            $arr['links']['mandate'] = $this->getMandate()->getId();
        }

        return $arr;
    }
}
