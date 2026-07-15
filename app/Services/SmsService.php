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
				'Email' => config('services.itexmo.email'),
				'Password' => config('services.itexmo.password'),
				'ApiCode' => config('services.itexmo.api_code'),
				'SenderId' => config('services.itexmo.sender_id'),
                'Recipients' => [$number],
                'Message'    => $message,
            ]
        );

        return [
            'status' => $response->status(),
            'body'   => $response->json(),
			'ver'=>'V3'
        ];
    }
}