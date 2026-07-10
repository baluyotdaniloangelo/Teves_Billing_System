<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function send($number, $message)
    {
        $response = Http::withOptions([
    'force_ip_resolve' => 'v4',
])->post(
            'https://api.itexmo.com/api/broadcast',
            [
                'Email'      => env('ITEXMO_EMAIL'),
                'Password'   => env('ITEXMO_PASSWORD'),
                'ApiCode'    => env('ITEXMO_API_CODE'),
                'Recipients' => [$number],
                'Message'    => $message,
                'SenderId'   => env('ITEXMO_SENDER_ID'),
            ]
        );

        return [
            'status' => $response->status(),
            'body'   => $response->json(),
			'ver'=>'V3'
        ];
    }
}