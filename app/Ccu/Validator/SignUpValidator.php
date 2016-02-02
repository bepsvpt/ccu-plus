<?php

namespace App\Ccu\Validator;

use GuzzleHttp\Client;
use Illuminate\Validation\Validator;

class SignUpValidator
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * Register constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Validate a given attribute against a rule.
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     * @param Validator $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, Validator $validator)
    {
        $data = $validator->getData();

        $response = $this->sendRequest($data['username'], $data['password']);

        $location = $response->getHeader('location');

        return isset($location[0]) && str_contains($location[0], 'sso_index.php');
    }

    /**
     * Send sign in request to ccu sso.
     *
     * @param string $username
     * @param string $password
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function sendRequest($username, $password)
    {
        return $this->client->post('http://portal.ccu.edu.tw/login_check.php', [
            'allow_redirects' => false,
            'form_params' => [
                'acc' => $username,
                'pass' => $password,
                'authcode' => '請輸入右邊文字',
            ],
        ]);
    }
}
