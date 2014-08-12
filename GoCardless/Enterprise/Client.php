<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 11:30
 */

namespace GoCardless\Enterprise;


use GoCardless\Enterprise\Exceptions\ApiException;
use GoCardless\Enterprise\Model\BankAccount;
use GoCardless\Enterprise\Model\Customer;
use GoCardless\Enterprise\Model\Mandate;
use GoCardless\Enterprise\Model\Model;
use GoCardless\Enterprise\Model\Payment;
use Guzzle\Http\Exception\BadResponseException;

class Client
{
    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    const ENDPOINT_CUSTOMER = "customers";

    const ENDPOINT_BANK = "customer_bank_accounts";

    const ENDPOINT_MANDATE = "mandates";

    const ENDPOINT_PAYMENTS = "payments";

    /**
     * @param \Guzzle\Http\Client $client
     * @param array $config
     * ["baseUrl" => ?, "username" => ?, "password" => ?]
     */
    public function __construct(\Guzzle\Http\Client $client, array $config)
    {
        $this->client = $client;
        $this->baseUrl = $config["baseUrl"];
        $this->username = $config["username"];
        $this->password = $config["password"];
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    public function createCustomer(Customer $customer)
    {
        $response = $this->post(self::ENDPOINT_CUSTOMER, $customer->toArray());
        $customer->fromArray($response);

        return $customer;
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     *
     * @return array
     */
    public function listCustomers($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CUSTOMER, $parameters);
        $customers = $this->responseToObjects(new Customer(), $response);

        return $customers;
    }

    /**
     * @param BankAccount $account
     * @return BankAccount
     */
    public function createCustomerBankAccount(BankAccount $account)
    {
        $response = $this->post(self::ENDPOINT_BANK, $account->toArray());
        $account->fromArray($response);
        return $account;
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     *
     * @return array
     */
    public function listCustomerBankAccounts($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_BANK, $parameters);
        $accounts = $this->responseToObjects(new BankAccount(), $response);

        return $accounts;
    }

    /**
     * @param Mandate $mandate
     * @return Mandate
     */
    public function createMandate(Mandate $mandate)
    {
        $response = $this->post(self::ENDPOINT_MANDATE, $mandate->toArray());
        $mandate->fromArray($response);
        return $mandate;
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     * @return array
     */
    public function listMandates($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_MANDATE, $parameters);
        $mandates = $this->responseToObjects(new Mandate(), $response);

        return $mandates;
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function createPayment(Payment $payment)
    {
        $response = $this->post(self::ENDPOINT_PAYMENTS, $payment->toArray());
        $payment->fromArray($response);
        return $response;
    }

    /**
     * @param int $limit
     * @param null $after
     * @param null $before
     * @return Payment[]
     */
    public function listPayments($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_MANDATE, $parameters);
        $payments = $this->responseToObjects(new Payment(), $response);

        return $payments;
    }

    /**
     * @param Model $example
     * @param $response
     * @return Model[]
     */
    protected function responseToObjects(Model $example, $response)
    {
        $objects = array_map(function($data) use ($example){
            $object = clone $example;
            $object->fromArray($data);
            return $object;
        }, $response);

        return $objects;
    }

    /**
     * @param $endpoint
     * @return string
     */
    protected function makeUrl($endpoint)
    {
        return $this->baseUrl.$endpoint;
    }

    /**
     * @param $endpoint
     * @param $body
     * @return array
     * @throws ApiException
     */
    protected function post($endpoint, $body)
    {
        try{
            $body = json_encode([$endpoint => $body]);
            $response = $this->client->post($this->makeUrl($endpoint), ["Content-Type" => "application/vnd.api+json"], $body)->setAuth($this->username, $this->password)->send();
            $responseArray = json_decode($response->getBody(true), true);
            return $responseArray[$endpoint];
        } catch(BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }

    /**
     * @param $endpoint
     * @param $parameters
     * @throws ApiException
     * @return array
     */
    protected function get($endpoint, $parameters)
    {
        try{
            $response = $this->client->get($this->makeUrl($endpoint), null, ["query" => $parameters])->setAuth($this->username, $this->password)->send();
            $responseArray = json_decode($response->getBody(true), true);
            return $responseArray[$endpoint];
        } catch (BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }
} 