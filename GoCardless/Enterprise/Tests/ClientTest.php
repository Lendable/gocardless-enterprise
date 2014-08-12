<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 13:09
 */

namespace GoCardless\Enterprise\Tests;


use GoCardless\Enterprise\Client;
use GoCardless\Enterprise\Model\BankAccount;
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
     * @param Customer $customer
     */
    public function testCreateBankAccount(Customer $customer)
    {
        $account = new BankAccount();
        $account->setAccountNumber("55779911");
        $account->setSortCode("200000");
        $account->setCountryCode("GB");
        $account->setAccountHolderName("Mr P D Pamment");
        $account->setCustomer($customer);

        $account = $this->getClient()->createCustomerBankAccount($account);

        $this->assertNotEquals(null, $account->getId());
        $this->assertNotEquals(null, $account->getCreatedAt());
    }

    public function testListBankAccounts()
    {
        $accounts = $this->getClient()->listCustomerBankAccounts();

        $this->assertTrue(is_array($accounts));
        foreach($accounts as $account)
        {
            $this->assertInstanceOf('GoCardless\Enterprise\Model\BankAccount', $account);
        }

        $account = reset($accounts);

        return $account;
    }

    /**
     * @depends testListBankAccounts
     * @param BankAccount $account
     */
    public function testCreateMandate(BankAccount $account)
    {
        $mandate = new Mandate();
        $mandate->setBankAccount($account);
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
     * @param Mandate $mandate
     */
    public function testCreatePayment(Mandate $mandate)
    {
        $payment = new Payment();

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
} 