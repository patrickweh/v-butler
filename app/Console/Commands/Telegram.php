<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class Telegram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // if a user without telegram id exists, check if there is a hello message and assign (update all users)
        if (! User::query()->whereNull('telegram_user_id')->exists()) {
            $response = json_decode(Http::get(
                'https://api.telegram.org/bot'.
                config('services.telegram-bot-api.token').
                '/getUpdates'
            )
            );

            if ($response->ok) {
                $result = $response->result;
                foreach ($result as $item) {
                    $firstname = $item->message->chat->first_name;
                    $lastname = $item->message->chat->last_name;
                    $id = $item->message->chat->id;
                    $user = User::query()
                        ->where('name', 'LIKE', '%'.$firstname.'%'.$lastname.'%')
                        ->first();
                    if ($user->exists()) {
                        $user->telegram_user_id = $id;
                        $user->save();
                    }
                }
            }
        }

        return 0;
    }
}
