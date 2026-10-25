<?php

return [
    'business_name' => env('SMS_BUSINESS_NAME', 'Teves Gas'),
    'business_signature' => "Greetings from;\nTeves Gas\nNational Highway, Brgy. San Juan, Madrid Surigao Del Sur",
    'contact_number' => env('SMS_CONTACT_NUMBER', ''),
    // Enable after the birthday list and final message have been reviewed.
    'birthday_enabled' => env('SMS_BIRTHDAY_ENABLED', false),
    'birthday_time' => env('SMS_BIRTHDAY_TIME', '08:00'),
    'timezone' => 'Asia/Manila',
    'templates' => [
        'lpg_order_thanks' => [
            'name' => 'LPG order thank you', 'scope' => 'order',
            'situation' => 'After an LPG order is confirmed',
            'message' => "Hi {client_name}! Salamat po sa inyong LPG order #{order_number}. Para sa mga katanungan, makipag-ugnayan lamang po sa amin.\n\n{business_signature}",
        ],
        'lpg_follow_up' => [
            'name' => 'LPG after-sales follow-up', 'scope' => 'order',
            'situation' => 'A set number of days after an LPG order or delivery',
            'message' => "Hi {client_name}! Kumusta po ang LPG mula sa order #{order_number}? Kung may concern po kayo, makipag-ugnayan lamang sa amin. Salamat po!\n\n{business_signature}",
        ],
        'lpg_refill' => [
            'name' => 'LPG refill reminder', 'scope' => 'order',
            'situation' => 'Planned 3–7 days after LPG order or delivery; final timing is not yet set',
            'message' => "Hi {client_name}! Baka kailangan na po ninyo ng Phoenix Super LPG refill. Maaari po kayong mag-order muli sa amin. Salamat sa pagpili sa amin bilang inyong kitchen buddy!\n\n{business_signature}",
        ],
        'birthday' => [
            'name' => 'Happy birthday', 'scope' => 'birthday',
            'situation' => 'On the customer birthday saved in the client record',
            'message' => "Wishing a very Happy Birthday, {client_first_name}, to one of our most valued customers! We hope your day is filled with warmth and joy.\n\nThank you for your continued trust and for choosing Phoenix Super LPG as your kitchen buddy!\n🎉🎊🎁\n\n{business_signature}",
        ],
        'service_notice' => [
            'name' => 'Service announcement', 'scope' => 'broadcast',
            'situation' => 'A general customer advisory; edit the details before sending',
            'message' => "Hi {client_name}! May mahalagang advisory po kami: [ilagay ang detalye at petsa]. Salamat po sa inyong pang-unawa.\n\n{business_signature}",
        ],
        'holiday_greeting' => [
            'name' => 'Holiday greeting', 'scope' => 'broadcast',
            'situation' => 'A seasonal greeting; edit the occasion before sending',
            'message' => "Hi {client_name}! Maligayang [okasyon] po sa inyo at sa inyong pamilya. Maraming salamat sa patuloy ninyong pagtangkilik!\n\n{business_signature}",
        ],
    ],
];
