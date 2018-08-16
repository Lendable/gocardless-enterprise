<?php

namespace Lendable\GoCardlessEnterprise;

use Lendable\GoCardlessEnterprise\Exceptions\ApiException;
use Lendable\GoCardlessEnterprise\Model\Creditor;
use Lendable\GoCardlessEnterprise\Model\CreditorBankAccount;
use Lendable\GoCardlessEnterprise\Model\Customer;
use Lendable\GoCardlessEnterprise\Model\CustomerBankAccount;
use Lendable\GoCardlessEnterprise\Model\Mandate;
use Lendable\GoCardlessEnterprise\Model\Model;
use Lendable\GoCardlessEnterprise\Model\Payment;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\BadResponseException;

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
    protected $secret;

    /**
     * @var array
     */
    protected $defaultHeaders;

    const ENDPOINT_CUSTOMER = 'customers';

    const ENDPOINT_CUSTOMER_BANK = 'customer_bank_accounts';

    const ENDPOINT_MANDATE = 'mandates';

    const ENDPOINT_MANDATE_PDF = 'mandate_pdfs';

    const ENDPOINT_PAYMENTS = 'payments';

    const ENDPOINT_CREDITORS = 'creditors';

    const ENDPOINT_CREDITOR_BANK = 'creditor_bank_accounts';

    /**
     * @param GuzzleClient $client
     * @param array $config
     * ["baseUrl" => ?, "gocardlessVersion" => ?, "webhook_secret" => ?, "token" => ?]
     */
    public function __construct(GuzzleClient $client, array $config)
    {
        $this->client = $client;
        $this->baseUrl = $config['baseUrl'];
        $this->secret = $config['webhook_secret'];
        $this->defaultHeaders = [
            'GoCardless-Version' => $config['gocardlessVersion'],
            'Authorization' => sprintf('Bearer %s', $config['token']),
        ];
    }

    /**
     * @param string $content
     * @param string $signature
     * @return bool
     */
    protected function validateWebhook($content, $signature)
    {
        return hash_equals(hash_hmac('sha256', $content, $this->secret), $signature);
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
     * @param string $id
     * @param Customer|null $customer
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
     * @param string|null $after
     * @param string|null $before
     * @param Customer|null $customer
     * @return array
     */
    public function listCustomers($limit = 50, $after = null, $before = null, Customer $customer = null)
    {
        $customer = null === $customer ? new Customer() : $customer;
        $parameters = array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
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
     * @param string $id
     * @param CustomerBankAccount|null $customerBankAccount
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
     * @param string|null $after
     * @param string|null $before
     * @param CustomerBankAccount|null $customerBankAccount
     * @return array
     */
    public function listCustomerBankAccounts($limit = 50, $after = null, $before = null, CustomerBankAccount $customerBankAccount = null)
    {
        $account = null === $customerBankAccount ? new CustomerBankAccount() : $customerBankAccount;

        $parameters = array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
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
     * @param string $id
     * @param Mandate|null $mandate
     * @return Mandate
     */
    public function getMandate($id, Mandate $mandate = null)
    {
        $mandate = null === $mandate ? new Mandate() : $mandate;
        $mandate->fromArray($this->get(self::ENDPOINT_MANDATE, [], $id));

        return $mandate;
    }

    /**
     * @param string $id
     * @return string
     * @throws \RuntimeException
     * @throws ApiException
     */
    public function getMandatePdf($id)
    {
        try {
            $body = ['links' => ['mandate' => $id]];
            $response = $this->post(self::ENDPOINT_MANDATE_PDF, $body);

            if (!array_key_exists('url', $response)) {
                return '';
            }
            $contents = file_get_contents($response['url']);
            if ($contents === false) {
                throw new \RuntimeException(sprintf('Cannot read the file contents of %s', $response['url']));
            }

            return $contents;
        } catch (BadResponseException $e) {
            throw ApiException::fromBadResponseException($e);
        }
    }

    /**
     * @param int $limit
     * @param string|null $after
     * @param string|null $before
     * @param Mandate|null $mandate
     * @return array
     */
    public function listMandates($limit = 50, $after = null, $before = null, Mandate $mandate = null)
    {
        $mandate = null === $mandate ? new Mandate() : $mandate;
        $parameters = array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
        $response = $this->get(self::ENDPOINT_MANDATE, $parameters);
        $mandates = $this->responseToObjects($mandate, $response);

        return $mandates;
    }

    /**
     * @param Mandate $mandate
     * @return Mandate
     * @throws \RuntimeException
     */
    public function cancelMandate(Mandate $mandate)
    {
        try {
            $body = '{"data":{}}';
            $endpoint = self::ENDPOINT_MANDATE;
            $path = $mandate->getId().'/actions/cancel';

            $response = $this->client->request(
                'POST',
                $this->makeUrl($endpoint, $path),
                [
                    'headers' => array_merge($this->defaultHeaders, ['Content-Type' => 'application/vnd.api+json']),
                    'body' => $body
                ]
            );
            $responseArray = json_decode((string) $response->getBody(), true);

            if (!isset($responseArray[$endpoint])) {
                throw new \RuntimeException('Malformed API response');
            }

            $mandate->fromArray($responseArray[$endpoint]);

            return $mandate;
        } catch (BadResponseException $e) {
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
     * @return Payment
     */
    public function cancelPayment(Payment $payment)
    {
        $path = $payment->getId().'/actions/cancel';
        $payment->fromArray($this->post(self::ENDPOINT_PAYMENTS, [], $path));

        return $payment;
    }

    /**
     * @param string $id
     * @param Payment|null $payment
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
     * @param string|null $after
     * @param string|null $before
     * @param array $options
     * @param Payment|null $payment
     * @return Payment[]
     */
    public function listPayments($limit = 50, $after = null, $before = null, array $options = [], Payment $payment = null)
    {
        $payment = null === $payment ? new Payment() : $payment;
        $parameters = array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);

        $parameters = array_merge($parameters, $options);

        $response = $this->get(self::ENDPOINT_PAYMENTS, $parameters);
        /** @var Payment[] $payments */
        $payments = $this->responseToObjects($payment, $response);

        return $payments;
    }

    /**
     * @param int $limit
     * @param string|null $after
     * @param string|null $before
     * @return Creditor[]
     */
    public function listCreditors($limit = 50, $after = null, $before = null)
    {
        $parameters = array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
        $response = $this->get(self::ENDPOINT_CREDITORS, $parameters);
        /** @var Creditor[] $creditors */
        $creditors = $this->responseToObjects(new Creditor(), $response);

        return $creditors;
    }

    /**
     * @param string $id
     * @param Creditor|null $creditor
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
        $data = $this->post(
            self::ENDPOINT_CREDITOR_BANK,
            array_merge_recursive(['set_as_default_payout_account' => $setAsDefault], $account->toArray())
        );

        $account->fromArray($data);

        return $account;
    }

    /**
     * @param Model $example
     * @param array $response
     * @return Model[]
     */
    protected function responseToObjects(Model $example, $response)
    {
        $objects = array_map(function ($data) use ($example) {
            $object = clone $example;
            $object->fromArray($data);

            return $object;
        }, $response);

        return $objects;
    }

    /**
     * @param string $endpoint
     * @param string|null $path
     * @return string
     */
    protected function makeUrl($endpoint, $path = null)
    {
        return $this->baseUrl.$endpoint.(is_string($path) ? '/'.$path : '');
    }

    /**
     * @param string $endpoint
     * @param array $body
     * @return array
     * @throws \RuntimeException
     * @throws ApiException
     */
    protected function post($endpoint, array $body, $path = false)
    {
        try {
            $response = $this->client->request(
                'POST',
                $this->makeUrl($endpoint, $path),
                [
                    'headers' => array_merge($this->defaultHeaders, ['Content-Type' => 'application/vnd.api+json']),
                    'body' => json_encode([$endpoint => $body])
                ]
            );
            $responseArray = json_decode((string) $response->getBody(), true);

            if (!isset($responseArray[$endpoint])) {
                throw new \RuntimeException('Malformed API response');
            }

            return $responseArray[$endpoint];
        } catch (BadResponseException $e) {
            throw ApiException::fromBadResponseException($e);
        }
    }

    /**
     * @param string $endpoint
     * @param array $parameters
     * @param string|null $path
     * @throws \RuntimeException
     * @throws ApiException
     * @return array
     */
    protected function get($endpoint, array $parameters = [], $path = null)
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->makeUrl($endpoint, $path),
                array_merge(['headers' => $this->defaultHeaders], ['query' => $parameters])
            );
            $responseArray = json_decode((string) $response->getBody(), true);

            if (!isset($responseArray[$endpoint])) {
                throw new \RuntimeException('Malformed API response');
            }

            return $responseArray[$endpoint];
        } catch (BadResponseException $e) {
            throw ApiException::fromBadResponseException($e);
        }
    }
}
