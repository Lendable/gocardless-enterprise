<?php

namespace Lendable\GoCardlessEnterprise\Tests\Integration;

use GuzzleHttp\Client as GuzzleClient;
use Lendable\GoCardlessEnterprise\Client;
use Lendable\GoCardlessEnterprise\Exceptions\IdempotentCreationConflictException;
use Lendable\GoCardlessEnterprise\Model\Creditor;
use Lendable\GoCardlessEnterprise\Model\Customer;
use Lendable\GoCardlessEnterprise\Model\CustomerBankAccount;
use Lendable\GoCardlessEnterprise\Model\Mandate;
use Lendable\GoCardlessEnterprise\Model\Payment;
use Lendable\GoCardlessEnterprise\Model\Payout;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private ?array $config = null;

    protected function getClient()
    {
        if ($this->config === null) {
            try {
                $this->config = require __DIR__.'/../../config.php';
            } catch (\Throwable $t) {
                $errorMessage = 'The integration tests required valid tests credentials to be provided in config.php. '
                    .'For help how to obtain test credentials visit https://manage-sandbox.gocardless.com/signup';

                throw new \RuntimeException($errorMessage);
            }
        }

        return new Client(new GuzzleClient(), $this->config);
    }

    public function test_create_customer()
    {
        $client = $this->getClient();

        $customer = new Customer();
        $customer->setEmail('phpunit+'.\time().\substr(\uniqid('test', true), 0, 3).'@example.com');
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

    public function test_list_customers()
    {
        $client = $this->getClient();
        $customers = $client->listCustomers();
        $this->assertTrue(\is_array($customers));
        foreach ($customers as $customer) {
            $this->assertInstanceOf(Customer::class, $customer);
        }

        return \reset($customers);
    }

    /**
     * @depends test_list_customers
     */
    public function test_get_customer(Customer $old)
    {
        $new = $this->getClient()->getCustomer($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends test_list_customers
     */
    public function test_create_customer_bank_account(Customer $customer)
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

    public function test_list_customer_bank_accounts()
    {
        $accounts = $this->getClient()->listCustomerBankAccounts();

        $this->assertTrue(\is_array($accounts));
        foreach ($accounts as $account) {
            $this->assertInstanceOf(CustomerBankAccount::class, $account);
        }

        return \reset($accounts);
    }

    /**
     * @depends test_list_customer_bank_accounts
     */
    public function test_get_customer_bank_account(CustomerBankAccount $old)
    {
        $new = $this->getClient()->getCustomerBankAccount($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    public function test_list_creditors()
    {
        $creditors = $this->getClient()->listCreditors();

        $this->assertTrue(\is_array($creditors));
        foreach ($creditors as $creditor) {
            $this->assertInstanceOf(Creditor::class, $creditor);
        }

        return \reset($creditors);
    }

    /**
     * @depends test_list_creditors
     */
    public function test_get_creditor(Creditor $old)
    {
        $new = $this->getClient()->getCreditor($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends test_list_customer_bank_accounts
     * @depends test_list_creditors
     */
    public function test_create_mandate(CustomerBankAccount $account, Creditor $creditor)
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

    public function test_list_mandates()
    {
        $mandates = $this->getClient()->listMandates();

        $this->assertTrue(\is_array($mandates));
        foreach ($mandates as $mandate) {
            $this->assertInstanceOf(Mandate::class, $mandate);
        }

        return \reset($mandates);
    }

    /**
     * @depends test_list_mandates
     */
    public function test_get_mandate(Mandate $old)
    {
        $new = $this->getClient()->getMandate($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends test_list_mandates
     */
    public function test_get_mandate_pdf(Mandate $old)
    {
        $mandate = $this->getClient()->getMandatePdf($old->getId());

        $this->assertEquals('%PDF', \substr($mandate, 0, 4));
    }

    /**
     * @depends test_list_mandates
     */
    public function test_create_payment(Mandate $mandate)
    {
        $payment = new Payment();
        $payment->setAmount(10000);
        $payment->setCurrency('GBP');
        $payment->setDescription('test');
        $payment->setMandate($mandate);
        $payment->setReference('1234567890');

        $payment = $this->getClient()->createPayment($payment);

        $this->assertNotNull($payment->getId());
        $this->assertNotNull($payment->getCreatedAt());
        $this->assertEquals('pending_submission', $payment->getStatus());
        $this->assertNotNull($payment->getChargeDate());
        $this->assertEquals('1234567890', $payment->getReference());
    }

    /**
     * @depends test_list_mandates
     */
    public function test_create_payment_with_metadata(Mandate $mandate)
    {
        $payment = new Payment();
        $payment->setAmount(10000);
        $payment->setCurrency('GBP');
        $payment->setDescription('test');
        $payment->setMetadata(['payment_id' => 12]);
        $payment->setMandate($mandate);
        $payment->setReference('1234567890');

        $payment = $this->getClient()->createPayment($payment);

        $this->assertNotNull($payment->getId());
        $this->assertNotNull($payment->getCreatedAt());
        $this->assertEquals('pending_submission', $payment->getStatus());
        $this->assertNotNull($payment->getChargeDate());
        $this->assertArrayHasKey('payment_id', $payment->getMetadata());
        $this->assertEquals(12, $payment->getMetadata()['payment_id']);
        $this->assertEquals('1234567890', $payment->getReference());
    }

    /**
     * @depends test_list_mandates
     */
    public function test_create_payment_with_idempotency_key(Mandate $mandate)
    {
        $idempotencyKey = 'key'.\uniqid();

        $payment = new Payment();
        $payment->setAmount(10000);
        $payment->setCurrency('GBP');
        $payment->setDescription('test');
        $payment->setMandate($mandate);
        $payment->setReference('1234567890');

        $payment = $this->getClient()->createPayment($payment, $idempotencyKey);

        $this->assertNotNull($payment->getId());
        $this->assertNotNull($payment->getCreatedAt());
        $this->assertEquals('pending_submission', $payment->getStatus());
        $this->assertNotNull($payment->getChargeDate());
        $this->assertEquals('1234567890', $payment->getReference());

        $retryPayment = new Payment();
        $retryPayment->setAmount(10000);
        $retryPayment->setCurrency('GBP');
        $retryPayment->setDescription('test');
        $retryPayment->setMandate($mandate);
        $retryPayment->setReference('1234567890');

        $this->expectException(IdempotentCreationConflictException::class);
        $this->expectExceptionCode(409);
        $this->expectExceptionMessage('A resource has already been created with this idempotency key');

        $this->getClient()->createPayment($retryPayment, $idempotencyKey);
    }

    public function test_list_payments()
    {
        $payments = $this->getClient()->listPayments();
        $this->assertTrue(\is_array($payments));
        foreach ($payments as $payment) {
            $this->assertInstanceOf(Payment::class, $payment);
        }

        return \reset($payments);
    }

    /**
     * @depends test_list_payments
     */
    public function test_get_payment(Payment $old)
    {
        $new = $this->getClient()->getPayment($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }

    /**
     * @depends test_list_mandates
     */
    public function test_cancel_mandate(Mandate $mandate)
    {
        $mandate = $this->getClient()->cancelMandate($mandate);

        $this->assertEquals('cancelled', $mandate->getStatus());
    }

    public function test_list_payouts()
    {
        $payouts = $this->getClient()->listPayouts();

        $this->assertTrue(\is_array($payouts));
        foreach ($payouts as $payout) {
            $this->assertInstanceOf(Payout::class, $payout);
        }

        return \reset($payouts);
    }

    /**
     * @depends test_list_payouts
     */
    public function test_get_payout(Payout $old)
    {
        $new = $this->getClient()->getPayout($old->getId());

        $newArray = $new->toArray();
        $oldArray = $old->toArray();

        $this->assertEquals($newArray, $oldArray);
    }
}
