<?php

namespace App\Helpers;

use PhpXmlRpc\Polyfill\XmlRpc;

class Smart1XMLRPCClient
{
    public function __construct($uri)
    {
        $this->uri = $uri;
        $this->curl_hdl = null;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close()
    {
        if ($this->curl_hdl !== null) {
            curl_close($this->curl_hdl);
        }
        $this->curl_hdl = null;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
        $this->close();
    }

    public function __call($method, $params)
    {
        $xml = XmlRpc\XmlRpc::xmlrpc_encode_request($method, $params);

        if ($this->curl_hdl === null) {
            // Create cURL resource
            $this->curl_hdl = curl_init();

            // Configure options
            curl_setopt($this->curl_hdl, CURLOPT_URL, $this->uri);
            curl_setopt($this->curl_hdl, CURLOPT_HEADER, 0);
            curl_setopt($this->curl_hdl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->curl_hdl, CURLOPT_POST, true);
        }

        curl_setopt($this->curl_hdl, CURLOPT_POSTFIELDS, $xml);

        $response = curl_exec($this->curl_hdl);
        $result = XmlRpc\XmlRpc::xmlrpc_decode_request($response, $method, 'UTF-8');
        //$result = xmlrpc_decode_request($response, $method,'UTF-8');

        return $result;
    }
}
