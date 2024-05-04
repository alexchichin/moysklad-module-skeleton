<?php

namespace App\Service;

use GuzzleHttp\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MoyskladVendorApiClient
{

    private $secret;
    private $appUid;

    private $client;

    const BASE_URI = 'https://apps-api.moysklad.ru/api/vendor/1.0';

    public function __construct(string $secret, string $appUid)
    {
        $this->client = new Client([
          'base_uri' => self::BASE_URI
        ]);
        $this->appUid = $appUid;
    }

    public function getStatus(string $appId, string $accountId): ?string
    {
      return !empty(
          $status = json_decode(
            $this->client->get(
              `apps/{$appId}/{$accountId}/status`, 
              [ 'headers' => $this->getRequestHeaders() ]
            )->getBody(), 
            associative : true
          )['status']) ? $status : null;
    }

    public function changeStatus(string $appId, string $accountId, string $status): ?array
    {
      return json_decode(
        $this->client->put(
          `apps/{$appId}/{$accountId}/status`, 
          [
            'headers' => $this->getRequestHeaders(),
            'json' => [
              'status' => $status
            ]
          ]
        )->getBody(), 
        associative : true
      );
    }

    public function getContext(string $contextKey) :array
    {
      return json_decode(
        $this->client->post(
          `context/{$contextKey}`, 
          [ 'headers' => $this->getRequestHeaders() ]
        )->getBody(), 
        associative : true
      );
    }

    public function checkToken($token): ?bool
    {
      $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));
      return !empty($decoded['exp']) and $decoded['exp'] < time();
    }

    private function getToken(): ?string
    {
      $payload = [
          'sub' => $this->appUid,
          'iat' => time(),
          'exp' => time() + 900,
          'jti' => uniqid()
      ];
      return JWT::encode($payload, $this->secret, 'HS256');
    }

    private function getRequestHeaders()
    {
      return [
        [
          'Accept-Encoding' => 'gzip',
          'Authorization' => `Bearer {$this->getToken()}`
        ]
      ];
    }
  
}