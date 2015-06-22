<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 08/08/14
 * Time: 11:30
 */

namespace GoCardless\Enterprise;


use GoCardless\Enterprise\Exceptions\ApiException;
use GoCardless\Enterprise\Model\CreditorBankAccount;
use GoCardless\Enterprise\Model\CustomerBankAccount;
use GoCardless\Enterprise\Model\Creditor;
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
     * @var array
     */
    protected $defaultHeaders;

    /**
     * @var string
     */
    protected $password;

    const ENDPOINT_CUSTOMER = "customers";

    const ENDPOINT_CUSTOMER_BANK = "customer_bank_accounts";

    const ENDPOINT_MANDATE = "mandates";

    const ENDPOINT_PAYMENTS = "payments";

    const ENDPOINT_CREDITORS = "creditors";

    const ENDPOINT_CREDITOR_BANK = "creditor_bank_accounts";

    /**
     * @param \Guzzle\Http\Client $client
     * @param array $config
     * ["baseUrl" => ?, "username" => ?, "password" => ?]
     */
    public function __construct(\Guzzle\Http\Client $client, array $config)
    {
        $this->client = $client;
        $this->baseUrl = $config["baseUrl"];
        $this->defaultHeaders = [
            "GoCardless-Version" => $config["gocardlessVersion"],
            "Authorization" => "Bearer ". $config["token"]
        ];
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
     * @param $id
     * @return Customer
     */
    public function getCustomer($id)
    {
        $customer = new Customer();
        $customer->fromArray($this->get(self::ENDPOINT_CUSTOMER, [], $id));
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
     * @param CustomerBankAccount $account
     * @return CustomerBankAccount
     */
    public function createCustomerBankAccount(CustomerBankAccount $account)
    {
        $response = $this->post(self::ENDPOINT_CUSTOMER_BANK, $account->toArray());
        $account->fromArray($response);
        return $account;
    }

    /**
     * @param $id
     * @return CustomerBankAccount
     */
    public function getCustomerBankAccount($id)
    {
        $account = new CustomerBankAccount();
        $account->fromArray($this->get(self::ENDPOINT_CUSTOMER_BANK, [], $id));
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
        $response = $this->get(self::ENDPOINT_CUSTOMER_BANK, $parameters);
        $accounts = $this->responseToObjects(new CustomerBankAccount(), $response);

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
     * @param $id
     * @return Mandate
     */
    public function getMandate($id)
    {
        $mandate = new Mandate();
        $mandate->fromArray($this->get(self::ENDPOINT_MANDATE, [], $id));
        return $mandate;
    }


    public function getMandatePdf($id)
    {
        try{
            $response = $this->client->get($this->makeUrl(self::ENDPOINT_MANDATE, $id), $this->defaultHeaders + ["Accept" => "application/pdf", "GoCardless"])->send();
            return $response->getBody(true);
        } catch(BadResponseException $e) {
            throw ApiException::fromBadResponseException($e);
        }
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
     * @param Mandate $mandate
     * @return Mandate
     */
    public function cancelMandate(Mandate $mandate)
    {
        try{
            $body = '{"data":{}}';
            $endpoint = self::ENDPOINT_MANDATE;
            $path = $mandate->getId()."/actions/cancel";
            $response = $this->client->post($this->makeUrl($endpoint, $path), $this->defaultHeaders + ["Content-Type" => "application/vnd.api+json"], $body)->send();
            $responseArray = json_decode($response->getBody(true), true);
            $response = $responseArray[$endpoint];

            $mandate->fromArray($response);
            return $mandate;
        } catch(BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }

    /**
     * @param Payment $payment
     * @return Payment
     */
    public function createPayment(Payment $payment)
    {
        $response = $this->post(self::ENDPOINT_PAYMENTS, $payment->toArray());
        $payment->fromArray($response);
        return $payment;
    }

    /**
     * @param $id
     * @return Payment
     */
    public function getPayment($id)
    {
        $payment = new Payment();
        $payment->fromArray($this->get(self::ENDPOINT_PAYMENTS, [], $id));
        return $payment;
    }

    /**
     * @param int $limit
     * @param null $after
     * @param null $before
     * @param array $options
     * @return Payment[]
     */
    public function listPayments($limit = 50, $after = null, $before = null, array $options = [])
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);

        $parameters = array_merge($parameters, $options);

        $response = $this->get(self::ENDPOINT_PAYMENTS, $parameters);
        $payments = $this->responseToObjects(new Payment(), $response);

        return $payments;
    }


    /**
     * @param int $limit
     * @param null $after
     * @param null $before
     * @return Creditor[]
     */
    public function listCreditors($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CREDITORS, $parameters);
        $creditors = $this->responseToObjects(new Creditor(), $response);

        return $creditors;
    }

    /**
     * @param $id
     * @return Creditor
     */
    public function getCreditor($id)
    {
        $creditor = new Creditor();
        $creditor->fromArray($this->get(self::ENDPOINT_CREDITORS, [], $id));
        return $creditor;
    }

    /**
     * @param CreditorBankAccount $account
     * @param bool $setAsDefault
     * @return CreditorBankAccount
     */
    public function createCreditorBankAccount(CreditorBankAccount $account, $setAsDefault = false)
    {
        $response = $this->post(self::ENDPOINT_CREDITOR_BANK, ["set_as_default_payout_account" => $setAsDefault]+$account->toArray());
        $account->fromArray($response);

        return $account;
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
     * @param $path
     * @return string
     */
    protected function makeUrl($endpoint, $path = false)
    {
        return $this->baseUrl.$endpoint.($path ? "/".$path : "");
    }

    /**
     * @param string $endpoint
     * @param string $body
     * @return array
     * @throws ApiException
     */
    protected function post($endpoint, $body, $path = false)
    {
        try{
            $body = json_encode([$endpoint => $body]);
            $response = $this->client->post($this->makeUrl($endpoint, $path), $this->defaultHeaders + ["Content-Type" => "application/vnd.api+json"], $body)->send();
            $responseArray = json_decode($response->getBody(true), true);
            return $responseArray[$endpoint];
        } catch(BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @param string $path
     * @throws ApiException
     * @return array
     */
    protected function get($endpoint, $parameters = [], $path = null)
    {
        try{
            $response = $this->client->get($this->makeUrl($endpoint, $path), $this->defaultHeaders, ["query" => $parameters])->send();
            $responseArray = json_decode($response->getBody(true), true);
            return $responseArray[$endpoint];
        } catch (BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }
} 