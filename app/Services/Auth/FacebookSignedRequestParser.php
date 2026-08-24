<?php

namespace App\Services\Auth;

use InvalidArgumentException;

class FacebookSignedRequestParser
{
    /**
     * @return array<string, mixed>
     */
    public function parse(string $signedRequest, ?string $appSecret = null): array
    {
        $appSecret ??= config('services.facebook.client_secret');

        if (! is_string($appSecret) || $appSecret === '') {
            throw new InvalidArgumentException('Facebook app secret is not configured.');
        }

        $parts = explode('.', $signedRequest, 2);

        if (count($parts) !== 2) {
            throw new InvalidArgumentException('Malformed signed request.');
        }

        [$encodedSignature, $payload] = $parts;

        $signature = $this->base64UrlDecode($encodedSignature);
        $expectedSignature = hash_hmac('sha256', $payload, $appSecret, true);

        if (! hash_equals($expectedSignature, $signature)) {
            throw new InvalidArgumentException('Invalid signed request signature.');
        }

        $data = json_decode($this->base64UrlDecode($payload), true);

        if (! is_array($data)) {
            throw new InvalidArgumentException('Invalid signed request payload.');
        }

        if (strtoupper((string) ($data['algorithm'] ?? '')) !== 'HMAC-SHA256') {
            throw new InvalidArgumentException('Unsupported signed request algorithm.');
        }

        return $data;
    }

    private function base64UrlDecode(string $value): string
    {
        $decoded = base64_decode(strtr($value, '-_', '+/'), true);

        if ($decoded === false) {
            throw new InvalidArgumentException('Invalid signed request encoding.');
        }

        return $decoded;
    }
}
