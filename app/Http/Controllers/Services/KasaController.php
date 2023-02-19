<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Support\Facades\Log;

class KasaController extends Controller
{
    public function on(Device $device)
    {
        $this->sendCommand(1, $device->config['ip']);
        $device->is_on = true;
        $device->save();
    }

    public function off(Device $device)
    {
        $this->sendCommand(0, $device->config['ip']);
        $device->is_on = false;
        $device->save();
    }

    public function import()
    {
    }

    private function encrypt($clear_text, $first_key = 0xAB)
    {
        $buf = unpack('c*', $clear_text);
        $key = $first_key;
        for ($i = 1; $i < count($buf) + 1; $i++) {
            $buf[$i] = $buf[$i] ^ $key;
            $key = $buf[$i];
        }
        $array_map = array_map('chr', $buf);
        $clear_text = implode('', $array_map);
        $length = strlen($clear_text);
        $header = pack('N*', $length);

        return $header.$clear_text;
    }

    private function sendCommand(int $state, string $host)
    {
        if (! ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            Log::error('Couldn create socket ', [$errormsg]);

            return;
        }

        //Connect socket to remote server
        if (! socket_connect($sock, $host, 9999)) {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            Log::error('Could not connect to socket', [$errormsg]);

            return;
        }
        $messageToSend = '{"system":{"set_relay_state":{"state":'.$state.'}}}';
        $message = $this->encrypt($messageToSend);
        socket_send($sock, $message, strlen($message), 0);
        socket_close($sock);
    }
}
