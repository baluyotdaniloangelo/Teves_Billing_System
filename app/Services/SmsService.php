<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function send($number, $message)
    {
        $email = env('ITEXMO_EMAIL');
        $password = env('ITEXMO_PASSWORD');

        $endpoint = 'https://api.itexmo.com/api/broadcast';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($email . ':' . $password),
        ])->post($endpoint, [
            'Recipients' => [$number],
            'Message' => $message,
            'SenderId' => env('ITEXMO_SENDER_ID', 'TevesGas'),
			'ApiCode'    => env('ITEXMO_API_CODE'),
        ]);

        return [
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }
}