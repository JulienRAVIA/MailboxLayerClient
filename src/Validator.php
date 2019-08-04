<?php
declare(strict_types=1);

namespace Xylis\MailboxLayer;

use Exception;
use JamesHalsall\Hydrator\HydratorInterface;
use JamesHalsall\Hydrator\ObjectSetterFromArrayHydrator;

/**
 * Validator
 * @package Xylis\MailboxLayer
 * @author Julien RAVIA <julien.ravia@gmail.com>
 * @see https://mailboxlayer.com/documentation#api_specs
 * @see https://mailboxlayer.com/documentation#rate_limits
 *
 */
class Validator
{
    /**
     * @var string $apiKey
     * @see https://mailboxlayer.com/documentation#access_keys
     */
    private $apiKey;

    /** @var HydratorInterface */
    private $hydrator;

    /** @var array */
    private $emailAddresses = [];

    /** @var array */
    private $options;

    /**
     * Set API options and instantiate hydrator
     *
     * @param string $apiKey
     * @param bool $smtpCheck
     * @param bool $prettyFormat
     * @param bool $catchAllCheck
     */
    public function __construct(
        string $apiKey,
        bool $smtpCheck = true,
        bool $prettyFormat = false,
        bool $catchAllCheck = false
    ) {
        $this->apiKey = $apiKey;
        $this->hydrator = new ObjectSetterFromArrayHydrator;
        $this->setOption('access_key', $apiKey);
        $this->setOption('format', (int) $prettyFormat);
        $this->setOption('smtp', (int) $smtpCheck);
        $this->setOption('catch_all', (int) $catchAllCheck);
    }

    /**
     * Make API request with provided email address
     *
     * @param string $emailAddress
     * @return $this
     * @throws Exception
     */
    public function validate(string $emailAddress, bool $bypassFilterValidation = false): Email
    {
        // We check before making any request if email address is valid email address
        // useful to avoid useless requests
        if (filter_var($emailAddress, FILTER_VALIDATE_EMAIL) === false && $bypassFilterValidation == false) {
            throw new \InvalidArgumentException('This is not a email address');
        }

        // if the email address already have been verified, we return the $email object
        if (array_key_exists($emailAddress, $this->emailAddresses)) {
            return $this->emailAddresses[$emailAddress];
        }

        // we construct the url params
        $params = http_build_query(array_merge($this->options, [
            'email' => $emailAddress
        ]));

        // we make the request
        $response = $this->request("http://apilayer.net/api/check?{$params}");

        // if there is a error array key, so the request have failed so we throw an exception
        if (array_key_exists('error', $response)) {
            /** @see https://mailboxlayer.com/documentation#error_codes */
            throw new Exception(
                "{$response['error']['info']}  ({$response['error']['type']})",
                $response['error']['code']
            );
        }

        // hydrate the email object and return this
        $emailObject = $this->hydrator->hydrate(Email::class, $response);
        $this->emailAddresses[$emailAddress] = $emailObject;

        return $emailObject;
    }

    /**
     * Make CURL request
     *
     * @param string $url
     * @return array|null
     */
    private function request(string $url): ?array
    {
        /** @see https://mailboxlayer.com/documentation#make_api_request */
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /** @see https://mailboxlayer.com/documentation#api_response */
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $response;
    }

    /**
     * Set option from key
     *
     * @param string $key
     * @param bool $value
     */
    public function setOption(string $key, $value)
    {
        if (in_array($key, ['access_key', 'smtp', 'format', 'catch_all'])) {
            $this->options[$key] = $value;
        }
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
