<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 13:09
 */

namespace GoCardless\Enterprise\Tests;


use GoCardless\Enterprise\Client;
use GoCardless\Enterprise\Model\CustomerBankAccount;
use GoCardless\Enterprise\Model\Creditor;
use GoCardless\Enterprise\Model\Customer;
use GoCardless\Enterprise\Model\Mandate;
use GoCardless\Enterprise\Model\Payment;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $config = null;

    protected function getClient()
    {
        if(is_null($this->config)){
            $this->config = require(dirname(__FILE__)."/../../../config.php");
        }
        return new Client(new \Guzzle\Http\Client(), $this->config);
    }

    public function testCreateCustomer()
    {
        $client = $this->getClient();

        $customer = new Customer();
        $customer->setEmail("paul+".time().substr(uniqid(),0, 3)."@alphalend.com");
        $customer->setGivenName("Paul");
        $customer->setFamilyName("Pamment");
        $customer->setAddressLine1("Flat 3G");
        $customer->setCity("London");
        $customer->setPostalCode("E2 8ET");
        $customer->setCountryCode("GB");

        $customer = $client->createCustomer($customer);

        $this->assertNotEquals(null, $customer->getId());
        $this->assertNotEquals(null, $customer->getCreatedAt());
    }

    public function testListCustomers()
    {
        $client = $this->getClient();
        $customers = $client->listCustomers();
        $this->assertTrue(is_array($customers));
        foreach($customers as $customer){
            $this->assertInstanceOf('GoCardless\Enterprise\Model\Customer', $customer);
        }
        $customer = reset($customers);
        return $customer;
    }

    /**
     * @depends testListCustomers
     * @param Customer $old
     */
    public function testGetCustomer(Customer $old)
    {
        $new = $this->getClient()->getCustomer($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends testListCustomers
     * @param Customer $customer
     */
    public function testCreateCustomerBankAccount(Customer $customer)
    {
        $account = new CustomerBankAccount();
        $account->setAccountNumber("55779911");
        $account->setSortCode("200000");
        $account->setCountryCode("GB");
        $account->setAccountHolderName("Mr P D Pamment");
        $account->setCustomer($customer);

        $account = $this->getClient()->createCustomerBankAccount($account);

        $this->assertNotEquals(null, $account->getId());
        $this->assertNotEquals(null, $account->getCreatedAt());
    }

    public function testListCustomerBankAccounts()
    {
        $accounts = $this->getClient()->listCustomerBankAccounts();

        $this->assertTrue(is_array($accounts));
        foreach($accounts as $account)
        {
            $this->assertInstanceOf('GoCardless\Enterprise\Model\CustomerBankAccount', $account);
        }

        $account = reset($accounts);

        return $account;
    }

    /**
     * @depends testListCustomerBankAccounts
     * @param CustomerBankAccount $old
     */
    public function testGetCustomerBankAccount($old)
    {
        $new = $this->getClient()->getCustomerBankAccount($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends testListCustomerBankAccounts
     * @param CustomerBankAccount $account
     */
    public function testCreateMandate(CustomerBankAccount $account)
    {
        $mandate = new Mandate();
        $mandate->setCustomerBankAccount($account);
        $mandate->setScheme("bacs");

        $mandate = $this->getClient()->createMandate($mandate);

        $this->assertNotNull($mandate->getStatus());
        $this->assertNotNull($mandate->getId());
        $this->assertNotNull($mandate->getCreatedAt());
    }

    public function testListMandates()
    {
        $mandates = $this->getClient()->listMandates();

        $this->assertTrue(is_array($mandates));
        foreach($mandates as $mandate)
        {
            $this->assertInstanceOf('GoCardless\Enterprise\Model\Mandate', $mandate);
        }

        $mandate = reset($mandates);

        return $mandate;
    }

    /**
     * @depends testListMandates
     * @param Mandate $old
     */
    public function testGetMandate($old)
    {
        $new = $this->getClient()->getMandate($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends testListMandates
     * @param Mandate $old
     */
    public function testGetMandatePdf($old)
    {
        $mandate = $this->getClient()->getMandatePdf($old->getId());
    }

    public function testListCreditors()
    {
        $creditors = $this->getClient()->listCreditors();

        $this->assertTrue(is_array($creditors));
        foreach($creditors as $creditor)
        {
            $this->assertInstanceOf('GoCardless\Enterprise\Model\Creditor', $creditor);
        }

        $creditor = reset($creditors);

        return $creditor;
    }

    /**
     * @depends testListCreditors
     * @param Creditor $old
     */
    public function testGetCreditor($old)
    {
        $new = $this->getClient()->getCreditor($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends testListMandates
     * @depends testListCreditors
     * @param Mandate $mandate
     * @param Creditor $creditor
     */
    public function testCreatePayment(Mandate $mandate, Creditor $creditor)
    {
        $payment = new Payment();
        $payment->setAmount(10000);
        $payment->setCurrency("GBP");
        $payment->setDescription("test");
        $payment->setMandate($mandate);
        $payment->setCreditor($creditor);

        $payment = $this->getClient()->createPayment($payment);

        $this->assertNotNull($payment->getId());
        $this->assertNotNull($payment->getCreatedAt());
        $this->assertEquals("pending", $payment->getStatus());
        $this->assertNotNull($payment->getCollectedAt());
    }

    public function testListPayments()
    {
        $payments = $this->getClient()->listPayments();
        $this->assertTrue(is_array($payments));
        foreach($payments as $payment)
        {
            $this->assertInstanceOf('GoCardless\Enterprise\Model\Payment', $payment);
        }

        $payment = reset($payments);

        return $payment;
    }

    /**
     * @depends testListPayments
     * @param Payment $old
     */
    public function testGetPayment($old)
    {
        $new = $this->getClient()->getPayment($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }


} 