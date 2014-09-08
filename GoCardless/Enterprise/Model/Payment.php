<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 12/08/14
 * Time: 10:31
 */

namespace GoCardless\Enterprise\Model;


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
     * @param \GoCardless\Enterprise\Model\Mandate $mandate
     */
    public function setMandate($mandate)
    {
        $this->mandate = $mandate;
    }

    /**
     * @return \GoCardless\Enterprise\Model\Mandate
     */
    public function getMandate()
    {
        return $this->mandate;
    }

    /**
     * @param string $chage_date
     */
    public function setCollectedAt($chage_date)
    {
        $this->charge_date = $chage_date;
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
     * @param int $transaction_fee
     */
    public function setTransactionFee($transaction_fee)
    {
        $this->transaction_fee = $transaction_fee;
    }

    /**
     * @return int
     */
    public function getTransactionFee()
    {
        return $this->transaction_fee;
    }

    public function toArray()
    {
        $arr = parent::toArray();

        if(array_key_exists("mandate", $arr)){
            unset($arr["mandate"]);
        }

        if($this->getMandate()){
            $arr["links"]["mandate"] = $this->getMandate()->getId();
        }

        return $arr;
    }


} 