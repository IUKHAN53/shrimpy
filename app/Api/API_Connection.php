<?php

namespace App\Api;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class API_Connection
{
    public $key;
    public $secret;
    public $base_url;
    public $request_url;
    public $method;
    public $body;
    public $account;
    public $portfolio;
    public $exchange;


    public function __construct()
    {
        $this->key = env('SHRIMPY_KEY');
        $this->secret = env('SHRIMPY_SECRET');
        $this->base_url = env('SHRIMPY_BASE_URL');
        $this->account = env('SHRIMPY_DEFAULT_ACCOUNT');
        $this->portfolio = env('SHRIMPY_DEFAULT_PORTFOLIO');
        $this->exchange = env('SHRIMPY_DEFAULT_EXCHANGE');
        $this->secret = base64_decode($this->secret);
    }

    function connect(): ResponseInterface
    {
        $nonce = time();
        $valueToSign = $this->request_url . $this->method . $nonce . $this->body;
        $signed_value = base64_encode(hash_hmac('sha256', $valueToSign, $this->secret, true));
        $header = ['headers' =>
            [
                'Content-Type' => 'application/json',
                'SHRIMPY-API-NONCE' => $nonce,
                'SHRIMPY-API-KEY' => $this->key,
                'SHRIMPY-API-SIGNATURE' => $signed_value,
            ],
            'body' => $this->body,
        ];
        $client = new Client();
        return $client->request($this->method, $this->base_url . $this->request_url, $header);
    }

    function getAccounts(): array
    {
        $this->request_url = '/v1/accounts';
        $this->method = 'GET';
        $this->body = '';
        $response = $this->connect();
        return json_decode($response->getBody()->getContents());
    }

    function getPortfolio(): array
    {
        $this->request_url = '/v1/accounts/' . $this->account . '/portfolios';
        $this->method = 'GET';
        $this->body = '';
        $portfolio = [];
        $response = $this->connect();
        return json_decode($response->getBody()->getContents());

    }

    function updatePortfolio($body)
    {
        $this->request_url = '/v1/accounts/' . $this->account . '/portfolios/' . $this->portfolio . '/update';
        $this->method = 'POST';
        $this->body = $body;
        $response = $this->connect();
        $response = json_decode($response->getBody()->getContents());
        print_r($response);
    }

    function rebalanceAccount()
    {
        $this->request_url = '/v1/accounts/' . $this->account . '/rebalance';
        $this->method = 'POST';
        $this->body = '';
        $response = $this->connect();
        $response = json_decode($response->getBody()->getContents());
    }

    function getTickerData()
    {
        $this->request_url = '/v1/' . $this->exchange . '/ticker';
        $this->method = 'GET';
        $this->body = '';
        $response = $this->connect();
        return json_decode($response->getBody()->getContents());
    }
}
