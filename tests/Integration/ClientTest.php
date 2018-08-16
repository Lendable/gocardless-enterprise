<?php

namespace Lendable\GoCardlessEnterprise\Tests\Integration;

use Lendable\GoCardlessEnterprise\Client;
use Lendable\GoCardlessEnterprise\Model\CustomerBankAccount;
use Lendable\GoCardlessEnterprise\Model\Creditor;
use Lendable\GoCardlessEnterprise\Model\Customer;
use Lendable\GoCardlessEnterprise\Model\Mandate;
use Lendable\GoCardlessEnterprise\Model\Payment;
use GuzzleHttp\Client as GuzzleClient;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    protected function getClient()
    {
        if (is_null($this->config)) {
            $this->config = require dirname(__FILE__).'/../../../config.php';
        }

        return new Client(new GuzzleClient(), $this->config);
    }

    public function testCreateCustomer()
    {
        $client = $this->getClient();

        $customer = new Customer();
        $customer->setEmail('phpunit+'.time().substr(uniqid(), 0, 3).'@example.com');
        $customer->setGivenName('Php');
        $customer->setFamilyName('Unit');
        $customer->setAddressLine1('Apt 1');
        $customer->setCity('London');
        $customer->setPostalCode('W1 1WW');
        $customer->setCountryCode('GB');

        $customer = $client->createCustomer($customer);

        $this->assertNotEquals(null, $customer->getId());
        $this->assertNotEquals(null, $customer->getCreatedAt());
    }

    public function testListCustomers()
    {
        $client = $this->getClient();
        $customers = $client->listCustomers();
        $this->assertTrue(is_array($customers));
        foreach ($customers as $customer) {
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
        $account->setAccountNumber('55779911');
        $account->setBranchCode('200000');
        $account->setCountryCode('GB');
        $account->setAccountHolderName('Mr Php Unit');
        $account->setCustomer($customer);

        $account = $this->getClient()->createCustomerBankAccount($account);

        $this->assertNotEquals(null, $account->getId());
        $this->assertNotEquals(null, $account->getCreatedAt());
    }

    public function testListCustomerBankAccounts()
    {
        $accounts = $this->getClient()->listCustomerBankAccounts();

        $this->assertTrue(is_array($accounts));
        foreach ($accounts as $account) {
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

    public function testListCreditors()
    {
        $creditors = $this->getClient()->listCreditors();

        $this->assertTrue(is_array($creditors));
        foreach ($creditors as $creditor) {
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
     * @depends testListCustomerBankAccounts
     * @depends testListCreditors
     * @param CustomerBankAccount $account
     */
    public function testCreateMandate(CustomerBankAccount $account, Creditor $creditor)
    {
        $mandate = new Mandate();
        $mandate->setCustomerBankAccount($account);
        $mandate->setScheme('bacs');
        $mandate->setCreditor($creditor);

        $mandate = $this->getClient()->createMandate($mandate);

        $this->assertNotNull($mandate->getStatus());
        $this->assertNotNull($mandate->getId());
        $this->assertNotNull($mandate->getCreatedAt());
    }

    public function testListMandates()
    {
        $mandates = $this->getClient()->listMandates();

        $this->assertTrue(is_array($mandates));
        foreach ($mandates as $mandate) {
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

        $this->assertEquals('%PDF', substr($mandate, 0, 4));
    }

    /**
     * @depends testListMandates
     * @param Mandate $mandate
     */
    public function testCreatePayment(Mandate $mandate)
    {
        $payment = new Payment();
        $payment->setAmount(10000);
        $payment->setCurrency('GBP');
        $payment->setDescription('test');
        $payment->setMandate($mandate);

        $payment = $this->getClient()->createPayment($payment);

        $this->assertNotNull($payment->getId());
        $this->assertNotNull($payment->getCreatedAt());
        $this->assertEquals('pending_submission', $payment->getStatus());
        $this->assertNotNull($payment->getChargeDate());
    }

    /**
     * @depends testListMandates
     * @param Mandate $mandate
     */
    public function testCreatePaymentWithMetadata(Mandate $mandate)
    {
        $payment = new Payment();
        $payment->setAmount(10000);
        $payment->setCurrency('GBP');
        $payment->setDescription('test');
        $payment->setMetadata(['payment_id' => 12]);
        $payment->setMandate($mandate);

        $payment = $this->getClient()->createPayment($payment);

        $this->assertNotNull($payment->getId());
        $this->assertNotNull($payment->getCreatedAt());
        $this->assertEquals('pending_submission', $payment->getStatus());
        $this->assertNotNull($payment->getChargeDate());
        $this->assertArrayHasKey('payment_id', $payment->getMetadata());
        $this->assertEquals(12, $payment->getMetadata()['payment_id']);
    }

    public function testListPayments()
    {
        $payments = $this->getClient()->listPayments();
        $this->assertTrue(is_array($payments));
        foreach ($payments as $payment) {
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

    /**
     * @depends testListMandates
     * @param Mandate $mandate
     */
    public function testCancelMandate($mandate)
    {
        $mandate = $this->getClient()->cancelMandate($mandate);

        $this->assertEquals('cancelled', $mandate->getStatus());
    }
}
