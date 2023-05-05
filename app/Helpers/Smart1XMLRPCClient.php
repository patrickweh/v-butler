<?php

namespace App\Helpers;

use PhpXmlRpc\Polyfill\XmlRpc;

class Smart1XMLRPCClient
{
    public function __construct(public string $uri, private \CurlHandle|bool|null $curlHandle = null)
    {
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if ($this->curlHandle !== null) {
            curl_close($this->curlHandle);
        }

        $this->curlHandle = null;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
        $this->close();
    }

    public function __call($method, $params)
    {
        $xml = XmlRpc\XmlRpc::xmlrpc_encode_request($method, $params);

        if ($this->curlHandle === null) {
            // Create cURL resource
            $this->curlHandle = curl_init();

            // Configure options
            curl_setopt($this->curlHandle, CURLOPT_URL, $this->uri);
            curl_setopt($this->curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curlHandle, CURLOPT_POST, true);
        }

        curl_setopt($this->curlHandle, CURLOPT_POSTFIELDS, $xml);

        $response = curl_exec($this->curlHandle);
        //$result = xmlrpc_decode_request($response, $method,'UTF-8');

        return XmlRpc\XmlRpc::xmlrpc_decode_request($response, $method, 'UTF-8');
    }
}
