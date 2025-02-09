<?php

namespace App\Services\MusicProviders\Exceptions;

class ProviderException extends \Exception
{
    protected string $provider;

    protected ?string $resetTime = null;

    protected bool $isQuotaError = false;

    protected int $statusCode;

    public static function quotaExceeded(string $provider, string $resetTime): self
    {
        $exception = new self('La recherche est temporairement indisponible - Le quota quotidien est dépassé');
        $exception->provider = $provider;
        $exception->resetTime = $resetTime;
        $exception->isQuotaError = true;
        $exception->statusCode = 429;

        return $exception;
    }

    public static function badRequest(string $provider, string $message): self
    {
        $exception = new self($message);
        $exception->provider = $provider;
        $exception->statusCode = 400;

        return $exception;
    }

    public static function serviceUnavailable(string $provider): self
    {
        $exception = new self('La recherche est temporairement indisponible - Veuillez réessayer plus tard');
        $exception->provider = $provider;
        $exception->statusCode = 503;

        return $exception;
    }

    public function toArray(): array
    {
        return [
            'error' => true,
            'provider' => $this->provider,
            'message' => $this->getMessage(),
            'status_code' => $this->statusCode,
            'quota_exceeded' => $this->isQuotaError,
            'reset_time' => $this->resetTime,
        ];
    }
}
