<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Notifications\Telegram;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class DoorbirdController extends Controller
{
    public function trigger()
    {
        $service = Service::query()->where('controller', get_class($this))->first();
        $device = $service->devices()->first();

        $image = $this->getImage($device->service);
        $url = URL::temporarySignedRoute(
            'nuki.on',
            now()->addMinutes(5),
            [
                'device' => $device->id,
            ]
        );
        $path = Storage::path($image);
        $payload = [
            'button' => [
                'caption' => 'Öffnen',
                'url' => $url,
            ],
        ];

        $users = User::query()->whereNotNull('telegram_user_id')->get();
        foreach ($users as $user) {
            $user->notify(new Telegram('KLINGEL', $path, $payload));
        }

        return response()->json(['status' => 'success']);
    }

    private function getImage(Service $service)
    {
        $url = $service->url.'/bha-api/image.cgi';
        $url = rtrim($url, '/');
        $filename = 'doorbird.jpg';
        $response = Http::withBasicAuth($service->user, $service->password)->get($url)->body();
        Storage::put($filename, $response);

        return $filename;
    }
}
