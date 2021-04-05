<?php

namespace App;

use Illuminate\Http\Request;

class Domain
{
    const REDIRECT_HTTP = 'http://';
    const REDIRECT_HTTPS = 'https://';

    const REDIRECT_WWW = '//www.';
    const REDIRECT_NOWWW = '//';

    const REGEX_FILTER_WWW = '/(\/\/www\.|\/\/)/';
    const REGEX_FILTER_HTTPS = '/^(http:\/\/|https:\/\/)/';

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $translated;

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function isEqual(): bool {
        return $this->request->fullUrl() !== $this->translated();
    }

    public function diff(): bool {
        $this->translated = $this->translate();

        return $this->isEqual();
    }

    public function translated(): string {
        if (!$this->translated) {
            $this->translated = $this->translate();
        }
        return $this->translated;
    }

    public function translate(): string {
        $protocol = $this->getHttpProtocol();
        $filtered = preg_replace(self::REGEX_FILTER_HTTPS, $protocol, $this->request->fullUrl());
        return preg_replace(self::REGEX_FILTER_WWW, $this->getDomainPreference(), $filtered);
    }

    public function getHttpProtocol(): string {
        if (!$this->request->secure()) {
            return self::REDIRECT_HTTP;
        }
        return config('domain.protocol') ?? self::REDIRECT_HTTP;
    }

    public function getDomainPreference(): string {
        return config('domain.preferred') ?? self::REDIRECT_WWW; // If you want your site to be redirected to non www just use REDIRECT_NOWWW
    }
}