<?php

namespace App\Services\Lti;

use Illuminate\Support\Facades\Cache;
use Packback\Lti1p3\Interfaces\ICache;

class Lti13Cache implements ICache
{
    // Cache durations
    private const int NONCE_EXPIRY = 60 * 60; // 1 hour

    private const int LAUNCH_DATA_EXPIRY = 60 * 60 * 24; // 24 hours

    private const int ACCESS_TOKEN_EXPIRY = 60 * 60; // 1 hour

    public function getLaunchData(string $key): ?array
    {
        return Cache::get("lti1p3-launch-data-{$key}");
    }

    public function cacheLaunchData(string $key, array $jwtBody): void
    {
        Cache::put("lti1p3-launch-data-{$key}", $jwtBody, self::LAUNCH_DATA_EXPIRY);
    }

    public function cacheNonce(string $nonce, string $state): void
    {
        Cache::put("lti1p3-nonce-{$nonce}", $state, self::NONCE_EXPIRY);
    }

    public function checkNonceIsValid(string $nonce, string $state): bool
    {
        $cachedState = Cache::get("lti1p3-nonce-{$nonce}");
        if (! $cachedState) {
            return false;
        }

        // Remove the nonce after validation (one-time use)
        Cache::forget("lti1p3-nonce-{$nonce}");

        return $cachedState === $state;
    }

    public function cacheAccessToken(string $key, string $accessToken): void
    {
        Cache::put("lti1p3-access-token-{$key}", $accessToken, self::ACCESS_TOKEN_EXPIRY);
    }

    public function getAccessToken(string $key): ?string
    {
        return Cache::get("lti1p3-access-token-{$key}");
    }

    public function clearAccessToken(string $key): void
    {
        Cache::forget("lti1p3-access-token-{$key}");
    }
}
