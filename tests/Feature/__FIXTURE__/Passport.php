<?php

namespace Tests\Feature\__FIXTURE__;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

class Passport
{
    public static function generatePersonAccessToken()
    {
        $clientRepository = new ClientRepository();

        $client = $clientRepository->createPersonalAccessClient(
            null, 'Personal Client', env('APP_URL')
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
