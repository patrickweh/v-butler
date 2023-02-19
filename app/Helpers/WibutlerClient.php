<?php

namespace App\Helpers;

use App\Models\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class WibutlerClient
{
    protected string $method;

    protected Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
        $this->setMethod();
        if (! $service->token) {
            $this->getToken();
        }
    }

    public function setMethod(?string $method = null)
    {
        $this->method = $method ?: 'GET';
    }

    public function sendCommand(string $slug, array $params = [], ?string $method = null, array $body = [])
    {
        $url = rtrim($this->service->url.'/api/'.$slug.'/'.http_build_query($params), '/');
        $this->setMethod($method);
        $response = null;
        $client = new Client();
        try {
            $response = $client->request($this->method, $url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$this->service->token,
                ],
                'verify' => false,
                'json' => $body,
            ]);
        } catch (GuzzleException $e) {
            if ($e->getResponse()->getStatusCode() == 403) {
                $this->service->token = null;
                $this->getToken();

                return $this->sendCommand($slug, $params, $method, $body);
            }
        }

        return json_decode($response?->getBody()->getContents());
    }

    private function getToken()
    {
        $client = new Client();
        $response = $client->post(
            rtrim($this->service->url, '/').'/api/login',
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'verify' => false,
                'json' => [
                    'username' => $this->service->user,
                    'password' => $this->service->password,
                ],
            ]
        );

        $this->service->token = json_decode($response->getBody()->getContents())?->sessionToken;
        $this->service->save();
    }
}
