<?php

use App\Models\Customers;
use App\Models\Sites;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Smark\Smark\JSON;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote.')->hourly();

Artisan::command('sync', function () {

    // new api key

    function generateNewAPIAccessToken()
    {
        $postRequestForNewApiKey = Http::withHeaders([
            'Content-Type' => 'application/json',  // Optional, can be inferred from the `json` method
        ])->withOptions([
            'verify' => false,
        ])->post(env('OMADAC_SERVER').'/openapi/authorize/token?grant_type=client_credentials', [
            'omadacId' => env('OMADAC_ID'),
            'client_id' => env('CLIENT_ID'),
            'client_secret' => env('CLIENT_SECRET'),
        ]);

        // Decode the response body from JSON to an array
        $responseBody = json_decode($postRequestForNewApiKey->body(), true);  // Decode into an associative array

        return JSON::jsonUnshift('public/accessTokenStorage/accessTokens.json', $responseBody['result']);
    }

    // CUSTOMERS

    function queryCustomersDataFromTheDatabase($latestAccessTokenParam)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer AccessToken='.$latestAccessTokenParam, // Replace with your API key
        ])->withOptions([
            'verify' => false,
        ])->get(env('OMADAC_SERVER').'/openapi/v1/msp/950c1327d64a1b53de3882530e979b99/customers?page=1&pageSize=1000');

        Customers::whereNot('id', '')->delete();

        foreach ($response['result']['data'] as $key => $value) {
            Customers::create([
                'customerId' => isset($value['customerId']) ? $value['customerId'] : null,
                'name' => isset($value['customerName']) ? $value['customerName'] : null,
                'description' => isset($value['description']) ? $value['description'] : null, // Check if description exists
                'users_id' => 1, // changed
            ]);
        }
    }

    // END CUSTOMERS

    // SITES

    function querySitesDataFromTheDatabase($latestAccessTokenParam)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer AccessToken='.$latestAccessTokenParam, // Replace with your API key
        ])->withOptions([
            'verify' => false,
        ])->get(env('OMADAC_SERVER').'/openapi/v1/msp/950c1327d64a1b53de3882530e979b99/sites?page=1&pageSize=1000');

        Sites::whereNot('id', '')->delete();

        foreach ($response['result']['data'] as $key => $value) {
            Sites::create([
                'name' => isset($value['siteName']) ? $value['siteName'] : null,
                'siteId' => isset($value['siteId']) ? $value['siteId'] : null,
                'customerId' => isset($value['customerId']) ? $value['customerId'] : null,
                'customerName' => isset($value['customerName']) ? $value['customerName'] : null,
                'region' => isset($value['region']) ? $value['region'] : null,
                'timezone' => isset($value['timeZone']) ? $value['timeZone'] : null,
                'scenario' => isset($value['scenario']) ? $value['scenario'] : null,
                'wan' => isset($value['wan']) ? $value['wan'] : null,
                'connectedApNum' => isset($value['connectedApNum']) ? $value['connectedApNum'] : null,
                'disconnectedApNum' => isset($value['disconnectedApNum']) ? $value['disconnectedApNum'] : null,
                'isolatedApNum' => isset($value['isolatedApNum']) ? $value['isolatedApNum'] : null,
                'connectedSwitchNum' => isset($value['connectedSwitchNum']) ? $value['connectedSwitchNum'] : null,
                'disconnectedSwitchNum' => isset($value['disconnectedSwitchNum']) ? $value['disconnectedSwitchNum'] : null,
                'type' => isset($value['type']) ? $value['type'] : null,
            ]);
        }

        // Return a success response
        return response()->json([
            'message' => 'Sites data updated successfully!',
        ]);
    }

    // END SITES

    // get the stored latest access token

    $latestAccessToken = JSON::jsonRead('public/accessTokenStorage/accessTokens.json')[0]['accessToken'];

    // check the expiration

    $response = Http::withHeaders([
        'Authorization' => 'Bearer AccessToken='.$latestAccessToken, // Replace with your API key
    ])->withOptions([
        'verify' => false,
    ])->get('https://10.99.0.187:8043/openapi/v1/msp/950c1327d64a1b53de3882530e979b99/general-setting');

    $expirationBasisThroughErrorCode = $response['errorCode'];

    // if not expired

    if ($expirationBasisThroughErrorCode === 0) {

        queryCustomersDataFromTheDatabase($latestAccessToken);
        querySitesDataFromTheDatabase($latestAccessToken);
        
    } else {

        // if expired

        generateNewAPIAccessToken(); // generate new access token

        $latestAccessToken = JSON::jsonRead('public/accessTokenStorage/accessTokens.json')[0]['accessToken'];

        queryCustomersDataFromTheDatabase($latestAccessToken);
        querySitesDataFromTheDatabase($latestAccessToken);
    }

})->purpose('Sync data from the API.')->everyFiveMinutes();