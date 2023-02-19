<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;

class KostalService extends Controller
{
    private PendingRequest $client;

    private string $baseUrl;

    public function __construct()
    {
        $this->client = Http::withOptions([
            'verify' => false,
            'timeout' => 10,
        ])->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Accept-Language' => 'en-US',
        ]);

        $this->baseUrl = config('kostal.url');

        $this->login();
    }

    public function login(): self
    {
        $clientNonce = base64_encode(random_bytes(12));

        $startResponse = Http::post($this->baseUrl.'/api/v1/auth/start', [
            'username' => 'user',
            'nonce' => $clientNonce,
        ])->throw()->json();

        $serverNonce = base64_decode($startResponse['nonce']);
        $transactionId = base64_decode($startResponse['transactionId']);
        $salt = base64_decode($startResponse['salt']);
        $rounds = $startResponse['rounds'];

        $saltedPassword = hash_pbkdf2('sha256', config('kostal.password'), $salt, $rounds, 32, true);
        $clientKey = hash_hmac('sha256', 'Client Key', $saltedPassword, true);
        $storedKey = hash('sha256', $clientKey, true);

        $serverNonce = base64_encode($serverNonce);
        $salt = base64_encode($salt);
        $authMsg = "n=user,r=$clientNonce,r=$serverNonce,s=$salt,i=$rounds,c=biws,r=$serverNonce";
        $clientProof = $clientKey ^ hash_hmac('sha256', $authMsg, $storedKey, true);

        $serverKey = hash_hmac('sha256', 'Server Key', $saltedPassword, true);
        $serverSignature = hash_hmac('sha256', $authMsg, $serverKey, true);

        $finishResponse = Http::post($this->baseUrl.'/api/v1/auth/finish', [
            'transactionId' => base64_encode($transactionId),
            'proof' => base64_encode($clientProof),
        ])->throw()->json();

        $token = $finishResponse['token'];
        $signature = base64_decode($finishResponse['signature']);
        if ($signature !== $serverSignature) {
            throw new \Exception('Server signature mismatch.');
        }

        // Step 3 create session
        $protocolKey = hash_hmac('sha256', 'Session Key'.$authMsg.$clientKey, $storedKey, true);
        $sessionNonce = random_bytes(16);
        $cipher = openssl_encrypt($token, 'aes-256-gcm', $protocolKey, OPENSSL_RAW_DATA, $sessionNonce, $authTag);

        $sessionRequest = [
            // AES initialization vector
            'iv' => base64_encode($sessionNonce),
            // AES GCM tag
            'tag' => base64_encode($authTag),
            // ID of authentication transaction
            'transactionId' => base64_encode($transactionId),
            // Only the token or token and service code (separated by colon). Encrypted with
            // AES using the protocol key
            'payload' => base64_encode($cipher),
        ];

        $response = Http::post($this->baseUrl.'/api/v1/auth/create_session', $sessionRequest)->json();

        $this->client->withHeaders([
            'authorization' => 'Session '.$response['sessionId'],
        ]);

        return $this;
    }

    public function getInverterData(string $moduleId, array $processDataIds): Collection
    {
        $payload = new Fluent();
        $payload->moduleid = $moduleId;
        $payload->processdataids = $processDataIds;

        return collect(
            $this->client
                ->post($this->baseUrl.'/api/v1/processdata', [$payload])
                ->json('0.processdata')
        )->keyBy('id');
    }
}
