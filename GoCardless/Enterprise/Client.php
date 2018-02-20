<?php

namespace GoCardless\Enterprise;

use GoCardless\Enterprise\Exceptions\ApiException;
use GoCardless\Enterprise\Model\CreditorBankAccount;
use GoCardless\Enterprise\Model\CustomerBankAccount;
use GoCardless\Enterprise\Model\Creditor;
use GoCardless\Enterprise\Model\Customer;
use GoCardless\Enterprise\Model\Mandate;
use GoCardless\Enterprise\Model\Model;
use GoCardless\Enterprise\Model\Payment;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client as GuzzleClient;

class Client
{
    /**
     * @var GuzzleClient
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

    const ENDPOINT_MANDATE_PDF = "mandate_pdfs";

    const ENDPOINT_PAYMENTS = "payments";

    const ENDPOINT_CREDITORS = "creditors";

    const ENDPOINT_CREDITOR_BANK = "creditor_bank_accounts";

    /**
     * @param GuzzleClient $client
     * @param array $config
     * ["baseUrl" => ?, "username" => ?, "webhook_secret" => ?, "token" => ?]
     */
    public function __construct(GuzzleClient $client, array $config)
    {
        $this->client = $client;
        $this->baseUrl = $config["baseUrl"];
        $this->secret = $config["webhook_secret"];
        $this->defaultHeaders = [
            "GoCardless-Version" => $config["gocardlessVersion"],
            "Authorization" => "Bearer ". $config["token"]
        ];
    }

    protected function validateWebhook($content, $signature)
    {
        return hash_hmac("sha256", $content, $this->secret) == $signature;
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
     * @param Customer $customer
     * @return Customer
     */
    public function getCustomer($id, Customer $customer = null)
    {
        $customer = null === $customer ? new Customer() : $customer;
        $customer->fromArray($this->get(self::ENDPOINT_CUSTOMER, [], $id));

        return $customer;
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     * @param Customer $customer
     * @return array
     */
    public function listCustomers($limit = 50, $after = null, $before = null, Customer $customer = null)
    {
        $customer = null === $customer ? new Customer() : $customer;
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CUSTOMER, $parameters);
        $customers = $this->responseToObjects($customer, $response);

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
     * @param CustomerBankAccount $customerBankAccount
     * @return CustomerBankAccount
     */
    public function getCustomerBankAccount($id, CustomerBankAccount $customerBankAccount = null)
    {
        $account = null === $customerBankAccount ? new CustomerBankAccount() : $customerBankAccount;
        $account->fromArray($this->get(self::ENDPOINT_CUSTOMER_BANK, [], $id));

        return $account;
    }

    /**
     * @param int $limit
     * @param string $after
     * @param string $before
     * @param CustomerBankAccount $customerBankAccount
     * @return array
     */
    public function listCustomerBankAccounts($limit = 50, $after = null, $before = null, CustomerBankAccount $customerBankAccount = null)
    {
        $account = null === $customerBankAccount ? new CustomerBankAccount() : $customerBankAccount;

        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_CUSTOMER_BANK, $parameters);
        $accounts = $this->responseToObjects($account, $response);

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
     * @param Mandate $mandate
     * @return Mandate
     */
    public function getMandate($id, Mandate $mandate = null)
    {
        $mandate = null === $mandate ? new Mandate() : $mandate;
        $mandate->fromArray($this->get(self::ENDPOINT_MANDATE, [], $id));

        return $mandate;
    }


    public function getMandatePdf($id)
    {
        try{
            $body = ['links' => ['mandate' => (string)$id]];
            $response = $this->post(self::ENDPOINT_MANDATE_PDF, $body);

            return array_key_exists('url', $response)
                ? file_get_contents($response['url'])
                : '';
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
    public function listMandates($limit = 50, $after = null, $before = null, Mandate $mandate = null)
    {
        $mandate = null === $mandate ? new Mandate() : $mandate;
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);
        $response = $this->get(self::ENDPOINT_MANDATE, $parameters);
        $mandates = $this->responseToObjects($mandate, $response);

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

            $response = $this->client->post(
                $this->makeUrl($endpoint, $path),
                [
                    'headers' => array_merge($this->defaultHeaders, ['Content-Type' => 'application/vnd.api+json']),
                    'body' => $body
                ]
            );
            $responseArray = json_decode((string) $response->getBody(), true);

            $mandate->fromArray($responseArray[$endpoint]);

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
     * @param Payment $payment
     *
     * @return Payment
     */
    public function cancelPayment(Payment $payment)
    {
        $path = $payment->getId().'/actions/cancel';
        $payment->fromArray($this->post(self::ENDPOINT_PAYMENTS, [], $path));

        return $payment;
    }

    /**
     * @param $id
     * @param Payment $payment
     * @return Payment
     */
    public function getPayment($id, Payment $payment = null)
    {
        $payment = null === $payment ? new Payment() : $payment;
        $payment->fromArray($this->get(self::ENDPOINT_PAYMENTS, [], $id));

        return $payment;
    }

    /**
     * @param int $limit
     * @param null $after
     * @param null $before
     * @param array $options
     * @param Payment $payment
     * @return Model\Payment[]
     */
    public function listPayments($limit = 50, $after = null, $before = null, array $options = [], Payment $payment = null)
    {
        $payment = null === $payment ? new Payment() : $payment;
        $parameters = array_filter(["after" => $after, "before" => $before, "limit" => $limit]);

        $parameters = array_merge($parameters, $options);

        $response = $this->get(self::ENDPOINT_PAYMENTS, $parameters);
        $payments = $this->responseToObjects($payment, $response);

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
     * @param Creditor $creditor
     * @return Creditor
     */
    public function getCreditor($id, Creditor $creditor = null)
    {
        $creditor = null === $creditor ? new Creditor() : $creditor;
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
        $response = $this->client->post(
            self::ENDPOINT_CREDITOR_BANK,
            ['headers' => array_merge_recursive(['set_as_default_payout_account' => $setAsDefault], $account->toArray())]
        );
        $responseArray = json_decode((string) $response->getBody(), true);

        $account->fromArray($responseArray);

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
        return $this->baseUrl.$endpoint.($path ? '/'.$path : '');
    }

    /**
     * @param string $endpoint
     * @param string $body
     * @return array
     * @throws ApiException
     */
    protected function post($endpoint, $body, $path = false)
    {
        try {
            $response = $this->client->post(
                $this->makeUrl($endpoint, $path),
                [
                    'headers' => array_merge($this->defaultHeaders, ['Content-Type' => 'application/vnd.api+json']),
                    'body' => json_encode([$endpoint => $body])
                ]
            );
            $responseArray = json_decode((string) $response->getBody(), true);

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
        try {
            $response = $this->client->get(
                $this->makeUrl($endpoint, $path),
                array_merge(['headers' => $this->defaultHeaders], ['query' => $parameters])
            );
            $responseArray = json_decode((string) $response->getBody(), true);

            return $responseArray[$endpoint];
        } catch (BadResponseException $e){
            throw ApiException::fromBadResponseException($e);
        }
    }
}
