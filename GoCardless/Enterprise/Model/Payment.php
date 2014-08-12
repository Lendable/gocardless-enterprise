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
     * @var Mandate
     */
    protected $mandate;

    /**
     * @var Creditor
     */
    protected $creditor;

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


} 