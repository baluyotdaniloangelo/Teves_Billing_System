<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SmsTemplateService
{
    public const TOKENS = ['client_name', 'client_first_name', 'salutation', 'business_name', 'business_signature', 'contact_number', 'order_number'];

    public function all(): array
    {
        if (!Schema::hasTable('teves_sms_templates')) return config('sms.templates', []);
        return $this->library()->filter(function ($template) { return (bool) $template->is_active; })
            ->mapWithKeys(function ($template) {
                return [$template->template_key => ['name' => $template->name, 'scope' => $template->scope, 'situation' => $template->situation, 'message' => $template->message]];
            })->all();
    }

    public function library()
    {
        return DB::table('teves_sms_templates')->orderByDesc('is_active')->orderBy('id')->get();
    }

    public function validateMessage(string $message, string $scope, bool $allowDraft = false): void
    {
        preg_match_all('/\{([^{}]+)\}/u', $message, $matches);
        foreach ($matches[1] as $token) {
            if (!in_array($token, self::TOKENS, true) || ($token === 'order_number' && $scope !== 'order')) {
                throw ValidationException::withMessages(['sms_content' => 'Unsupported placeholder: {'.$token.'}.']);
            }
        }
        if (!$allowDraft && preg_match('/\[[^\]]+\]/u', $message)) {
            throw ValidationException::withMessages(['sms_content' => 'Replace the text in square brackets before sending.']);
        }
    }

    public function render(string $message, array $recipient, array $context = []): string
    {
        $gender = strtolower(trim($recipient['client_gender'] ?? ''));
        $salutation = in_array($gender, ['male', 'm'], true) ? 'Mr.' : (in_array($gender, ['female', 'f'], true) ? 'Ms.' : '');
        $values = [
            '{client_name}' => trim($recipient['client_name'] ?? '') ?: 'valued customer',
            '{client_first_name}' => trim($recipient['client_first_name'] ?? '') ?: 'valued customer',
            '{salutation}' => $salutation,
            '{business_name}' => config('sms.business_name', 'Teves Gas'),
            '{business_signature}' => config('sms.business_signature'),
            '{contact_number}' => config('sms.contact_number', ''),
        ];
        if (isset($context['order_number'])) $values['{order_number}'] = (string) $context['order_number'];
        $result = trim(strtr($message, $values));
        if (mb_strlen($result) > 1000) {
            throw ValidationException::withMessages(['sms_content' => 'A personalized message exceeds 1,000 characters. Shorten the template.']);
        }
        return $result;
    }

    public static function normalizeMobile(?string $value): ?string
    {
        $number = preg_replace('/\D/', '', $value ?? '');
        if (preg_match('/^09\d{9}$/', $number)) $number = '63'.substr($number, 1);
        if (preg_match('/^9\d{9}$/', $number)) $number = '63'.$number;
        return preg_match('/^639\d{9}$/', $number) ? $number : null;
    }
}
