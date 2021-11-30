<?php

namespace Lendable\GoCardlessEnterprise;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\BadResponseException;
use Lendable\GoCardlessEnterprise\Exceptions\ApiException;
use Lendable\GoCardlessEnterprise\Exceptions\IdempotentCreationConflictException;
use Lendable\GoCardlessEnterprise\Model\Creditor;
use Lendable\GoCardlessEnterprise\Model\CreditorBankAccount;
use Lendable\GoCardlessEnterprise\Model\Customer;
use Lendable\GoCardlessEnterprise\Model\CustomerBankAccount;
use Lendable\GoCardlessEnterprise\Model\Mandate;
use Lendable\GoCardlessEnterprise\Model\Model;
use Lendable\GoCardlessEnterprise\Model\Payment;
use Lendable\GoCardlessEnterprise\Model\Payout;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private const ENDPOINT_CUSTOMER = 'customers';

    private const ENDPOINT_CUSTOMER_BANK = 'customer_bank_accounts';

    private const ENDPOINT_MANDATE = 'mandates';

    private const ENDPOINT_MANDATE_PDF = 'mandate_pdfs';

    private const ENDPOINT_PAYMENTS = 'payments';

    private const ENDPOINT_CREDITORS = 'creditors';

    private const ENDPOINT_CREDITOR_BANK = 'creditor_bank_accounts';

    private const ENDPOINT_PAYOUT = 'payouts';

    private GuzzleClient $client;

    private string $baseUrl;

    private string $secret;

    private array $defaultHeaders;

    /**
     * @param array $config
     *                      ["baseUrl" => ?, "gocardlessVersion" => ?, "webhook_secret" => ?, "token" => ?]
     */
    public function __construct(GuzzleClient $client, array $config)
    {
        $this->client = $client;
        $this->baseUrl = $config['baseUrl'];
        $this->secret = $config['webhook_secret'];
        $this->defaultHeaders = [
            'GoCardless-Version' => $config['gocardlessVersion'],
            'Authorization' => \sprintf('Bearer %s', $config['token']),
        ];
    }

    protected function validateWebhook(string $content, string $signature): bool
    {
        return \hash_equals(\hash_hmac('sha256', $content, $this->secret), $signature);
    }

    public function createCustomer(Customer $customer): Customer
    {
        $response = $this->post(self::ENDPOINT_CUSTOMER, $customer->toArray());
        $customer->fromArray($response);

        return $customer;
    }

    public function getCustomer(string $id, Customer $customer = null): Customer
    {
        $customer = null === $customer ? new Customer() : $customer;
        $customer->fromArray($this->get(self::ENDPOINT_CUSTOMER, [], $id));

        return $customer;
    }

    public function listCustomers(int $limit = 50, ?string $after = null, ?string $before = null, ?Customer $customer = null): array
    {
        $customer = null === $customer ? new Customer() : $customer;
        $parameters = \array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
        $response = $this->get(self::ENDPOINT_CUSTOMER, $parameters);

        return $this->responseToObjects($customer, $response);
    }

    public function createCustomerBankAccount(CustomerBankAccount $account): CustomerBankAccount
    {
        $response = $this->post(self::ENDPOINT_CUSTOMER_BANK, $account->toArray());
        $account->fromArray($response);

        return $account;
    }

    public function getCustomerBankAccount(string $id, CustomerBankAccount $customerBankAccount = null): CustomerBankAccount
    {
        $account = null === $customerBankAccount ? new CustomerBankAccount() : $customerBankAccount;
        $account->fromArray($this->get(self::ENDPOINT_CUSTOMER_BANK, [], $id));

        return $account;
    }

    public function listCustomerBankAccounts(int $limit = 50, ?string $after = null, ?string $before = null, ?CustomerBankAccount $customerBankAccount = null): array
    {
        $account = null === $customerBankAccount ? new CustomerBankAccount() : $customerBankAccount;

        $parameters = \array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
        $response = $this->get(self::ENDPOINT_CUSTOMER_BANK, $parameters);

        return $this->responseToObjects($account, $response);
    }

    public function createMandate(Mandate $mandate): Mandate
    {
        $response = $this->post(self::ENDPOINT_MANDATE, $mandate->toArray());
        $mandate->fromArray($response);

        return $mandate;
    }

    public function getMandate(string $id, Mandate $mandate = null): Mandate
    {
        $mandate = null === $mandate ? new Mandate() : $mandate;
        $mandate->fromArray($this->get(self::ENDPOINT_MANDATE, [], $id));

        return $mandate;
    }

    /**
     * @throws \RuntimeException
     * @throws ApiException
     */
    public function getMandatePdf(string $id): string
    {
        try {
            $body = ['links' => ['mandate' => $id]];
            $response = $this->post(self::ENDPOINT_MANDATE_PDF, $body);

            if (!\array_key_exists('url', $response)) {
                return '';
            }

            $contents = \file_get_contents($response['url']);
            if ($contents === false) {
                throw new \RuntimeException(\sprintf('Cannot read the file contents of %s', $response['url']));
            }

            return $contents;
        } catch (BadResponseException $e) {
            throw ApiException::fromBadResponseException($e);
        }
    }

    public function listMandates(int $limit = 50, ?string $after = null, ?string $before = null, ?Mandate $mandate = null): array
    {
        $mandate = null === $mandate ? new Mandate() : $mandate;
        $parameters = \array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
        $response = $this->get(self::ENDPOINT_MANDATE, $parameters);

        return $this->responseToObjects($mandate, $response);
    }

    /**
     * @throws \RuntimeException
     */
    public function cancelMandate(Mandate $mandate): Mandate
    {
        try {
            $body = '{"data":{}}';
            $endpoint = self::ENDPOINT_MANDATE;
            $path = $mandate->getId().'/actions/cancel';

            $response = $this->client->request(
                'POST',
                $this->makeUrl($endpoint, $path),
                [
                    'headers' => \array_merge($this->defaultHeaders, ['Content-Type' => 'application/vnd.api+json']),
                    'body' => $body,
                ]
            );
            $responseArray = \json_decode((string) $response->getBody(), true);

            if (!isset($responseArray[$endpoint]) || !\is_array($responseArray[$endpoint])) {
                throw new \RuntimeException('Malformed API response');
            }

            $mandate->fromArray($responseArray[$endpoint]);

            return $mandate;
        } catch (BadResponseException $e) {
            throw ApiException::fromBadResponseException($e);
        }
    }

    public function createPayment(Payment $payment, ?string $idempotencyKey = null): Payment
    {
        $response = $this->post(
            self::ENDPOINT_PAYMENTS,
            $payment->toArray(),
            null,
            $idempotencyKey
        );

        $payment->fromArray($response);

        return $payment;
    }

    public function cancelPayment(Payment $payment): Payment
    {
        $path = $payment->getId().'/actions/cancel';
        $payment->fromArray($this->post(self::ENDPOINT_PAYMENTS, [], $path));

        return $payment;
    }

    public function getPayment(string $id, Payment $payment = null): Payment
    {
        $payment = null === $payment ? new Payment() : $payment;
        $payment->fromArray($this->get(self::ENDPOINT_PAYMENTS, [], $id));

        return $payment;
    }

    /**
     * @return Payment[]
     */
    public function listPayments(int $limit = 50, ?string $after = null, ?string $before = null, array $options = [], Payment $payment = null): array
    {
        $payment = null === $payment ? new Payment() : $payment;
        $parameters = \array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);

        $parameters = \array_merge($parameters, $options);

        $response = $this->get(self::ENDPOINT_PAYMENTS, $parameters);

        return $this->responseToObjects($payment, $response);
    }

    /**
     * @return Creditor[]
     */
    public function listCreditors(int $limit = 50, ?string $after = null, ?string $before = null): array
    {
        $parameters = \array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);
        $response = $this->get(self::ENDPOINT_CREDITORS, $parameters);

        return $this->responseToObjects(new Creditor(), $response);
    }

    public function getCreditor(string $id, Creditor $creditor = null): Creditor
    {
        $creditor = null === $creditor ? new Creditor() : $creditor;
        $creditor->fromArray($this->get(self::ENDPOINT_CREDITORS, [], $id));

        return $creditor;
    }

    public function createCreditorBankAccount(CreditorBankAccount $account, bool $setAsDefault = false): CreditorBankAccount
    {
        $data = $this->post(
            self::ENDPOINT_CREDITOR_BANK,
            \array_merge_recursive(['set_as_default_payout_account' => $setAsDefault], $account->toArray())
        );

        $account->fromArray($data);

        return $account;
    }

    public function getPayout(string $id, Payout $payout = null): Payout
    {
        $payout = null === $payout ? new Payout() : $payout;
        $payout->fromArray($this->get(self::ENDPOINT_PAYOUT, [], $id));

        return $payout;
    }

    /**
     * @return Payout[]
     */
    public function listPayouts(int $limit = 50, ?string $after = null, ?string $before = null, array $options = [], Payout $payout = null): array
    {
        $payout = null === $payout ? new Payout() : $payout;
        $parameters = \array_filter(['after' => $after, 'before' => $before, 'limit' => $limit]);

        $parameters = \array_merge($parameters, $options);

        $response = $this->get(self::ENDPOINT_PAYOUT, $parameters);

        return $this->responseToObjects($payout, $response);
    }

    /**
     * @return Model[]
     */
    protected function responseToObjects(Model $example, array $response): array
    {
        $objects = \array_map(static function ($data) use ($example) {
            $object = clone $example;
            $object->fromArray($data);

            return $object;
        }, $response);

        return $objects;
    }

    protected function makeUrl(string $endpoint, ?string $path = null): string
    {
        return $this->baseUrl.$endpoint.(\is_string($path) ? '/'.$path : '');
    }

    /**
     * @throws \RuntimeException
     * @throws ApiException
     */
    protected function post(string $endpoint, array $body, ?string $path = null, ?string $idempotencyKey = null): array
    {
        $headers = \array_merge($this->defaultHeaders, ['Content-Type' => 'application/vnd.api+json']);

        if ($idempotencyKey !== null) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }

        try {
            $response = $this->client->request(
                'POST',
                $this->makeUrl($endpoint, $path),
                [
                    'headers' => $headers,
                    'body' => \json_encode([$endpoint => $body]),
                ]
            );
            $responseArray = \json_decode((string) $response->getBody(), true);

            if (!isset($responseArray[$endpoint])) {
                throw new \RuntimeException('Malformed API response');
            }

            return $responseArray[$endpoint];
        } catch (BadResponseException $exception) {
            if ($this->isIdempotentCreationConflict($exception)) {
                throw IdempotentCreationConflictException::fromBadResponseException($exception);
            }

            throw ApiException::fromBadResponseException($exception);
        }
    }

    private function isIdempotentCreationConflict(BadResponseException $exception): bool
    {
        $response = $exception->getResponse();
        \assert($response instanceof ResponseInterface);
        $responseArray = \json_decode((string) $response->getBody(), true);

        if (!isset($responseArray['error']['errors'])) {
            return false;
        }

        $errors = $responseArray['error']['errors'];

        if (!\is_array($errors)) {
            return false;
        }

        foreach ($errors as $error) {
            if (isset($error['reason']) && $error['reason'] === 'idempotent_creation_conflict') {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws \RuntimeException
     * @throws ApiException
     */
    protected function get(string $endpoint, array $parameters = [], ?string $path = null): array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->makeUrl($endpoint, $path),
                \array_merge(['headers' => $this->defaultHeaders], ['query' => $parameters])
            );
            $responseArray = \json_decode((string) $response->getBody(), true);

            if (!isset($responseArray[$endpoint])) {
                throw new \RuntimeException('Malformed API response');
            }

            return $responseArray[$endpoint];
        } catch (BadResponseException $exception) {
            throw ApiException::fromBadResponseException($exception);
        }
    }
}
