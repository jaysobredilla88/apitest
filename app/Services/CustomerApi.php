<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Customers;

class CustomerApi
{
    const API_URL = 'http://randomuser.me/api';
    const NUM_RESULTS = 100;
    const NATIONALITY = 'AU';

    public function importAPI()
    {
        $url = self::API_URL;

        $params = [
            'results' => self::NUM_RESULTS,
            'nat' => self::NATIONALITY,
        ];

        $url .= '?' . http_build_query($params);

        $response = Http::get($url);

        if ($response->successful()) {
            $body = $response->json();

            foreach ($body['results'] as $customer) {
                $cu = [
                    'first_name' => $customer['name']['first'],
                    'last_name' => $customer['name']['last'],
                    'email' => $customer['email'],
                    'gender' => $customer['gender'],
                    'country' => $customer['location']['country'],
                    'username' => $customer['login']['username'],
                    'password' => $customer['login']['password'],
                    'city' => $customer['location']['city'],
                    'phone' => $customer['phone'],
                ];

                Customers::saveCustomer($cu);
            }
        }
    }
}
