<?php

namespace App\Services\Lti;

use Illuminate\Http\RedirectResponse;
use Packback\Lti1p3\DeepLinkResources\Resource;
use Packback\Lti1p3\Interfaces\ICache;
use Packback\Lti1p3\Interfaces\ICookie;
use Packback\Lti1p3\Interfaces\IDatabase;
use Packback\Lti1p3\Interfaces\ILtiServiceConnector;
use Packback\Lti1p3\JwksEndpoint;
use Packback\Lti1p3\LtiException;
use Packback\Lti1p3\LtiMessageLaunch;
use Packback\Lti1p3\LtiOidcLogin;

class Lti13Service
{
    public function __construct(
        private readonly IDatabase $database,
        private readonly ICache $cache,
        private readonly ICookie $cookie,
        private readonly ILtiServiceConnector $connector
    ) {}

    /**
     * Handle OIDC login request from LMS
     */
    public function login(array $request, string $launchUrl): RedirectResponse
    {
        $login = new LtiOidcLogin($this->database, $this->cache, $this->cookie);

        try {
            $redirectUrl = $login->getRedirectUrl($launchUrl, $request);

            return redirect($redirectUrl);
        } catch (LtiException $e) {
            // Handle error, log it, etc.
            throw $e;
        }
    }

    /**
     * Handle and validate the LTI message launch
     */
    public function validateLaunch(array $request): LtiMessageLaunch
    {
        $launch = LtiMessageLaunch::new(
            $this->database,
            $this->cache,
            $this->cookie,
            $this->connector
        );

        try {
            return $launch->initialize($request);
        } catch (LtiException $e) {
            // Handle error, log it, etc.
            throw $e;
        }
    }

    /**
     * Retrieve a previously validated launch
     */
    public function getLaunchFromCache(string $launchId): LtiMessageLaunch
    {
        try {
            return LtiMessageLaunch::fromCache(
                $launchId,
                $this->database,
                $this->cache,
                $this->cookie,
                $this->connector
            );
        } catch (LtiException $e) {
            // Handle error, log it, etc.
            throw $e;
        }
    }

    /**
     * Generate Deep Linking response
     */
    public function createDeepLinkResponse(string $launchId, array $resources = []): array
    {
        $launch = $this->getLaunchFromCache($launchId);

        if (! $launch->isDeepLinkLaunch()) {
            throw new LtiException('Not a deep linking launch');
        }

        $dl = $launch->getDeepLink();

        // Create resources if none provided
        if (empty($resources)) {
            $resources = [
                Resource::new()
                    ->setUrl(route('lti.launch'))
                    ->setTitle('My Resource')
                    ->setText('Resource description'),
            ];
        }

        // Get JWT for the response
        $jwt = $dl->getResponseJwt($resources);
        $returnUrl = $dl->returnUrl();

        // Return the necessary data to create an auto-posting form
        return [
            'jwt' => $jwt,
            'return_url' => $returnUrl,
        ];
    }

    /**
     * Provide a JWKS endpoint for platforms to verify our signatures
     */
    public function getPublicJwks(): array
    {
        return JwksEndpoint::fromIssuer($this->database, url('/'))->getPublicJwks();
    }
}
