<?php

namespace App\Services\Lti;

use App\Models\Deployment;
use App\Models\Issuer;
use Packback\Lti1p3\Interfaces\IDatabase;
use Packback\Lti1p3\Interfaces\ILtiDeployment;
use Packback\Lti1p3\Interfaces\ILtiRegistration;
use Packback\Lti1p3\LtiDeployment;
use Packback\Lti1p3\LtiRegistration;

class Lti13Database implements IDatabase
{
    /**
     * Find an LTI registration by issuer and optional client ID
     */
    public function findRegistrationByIssuer(string $iss, ?string $clientId = null): ?ILtiRegistration
    {
        $query = Issuer::where('issuer', $iss);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        $issuer = $query->first();

        if (! $issuer) {
            return null;
        }

        return LtiRegistration::new([
            'issuer' => $issuer->issuer,
            'clientId' => $issuer->client_id,
            'keySetUrl' => $issuer->key_set_url,
            'authTokenUrl' => $issuer->auth_token_url,
            'authLoginUrl' => $issuer->auth_login_url,
            'authServer' => $issuer->auth_server,
            'toolPrivateKey' => $issuer->tool_private_key,
            'kid' => $issuer->kid,
        ]);
    }

    /**
     * Find a deployment by issuer, deployment ID, and optional client ID
     */
    public function findDeployment(string $iss, string $deploymentId, ?string $clientId = null): ?ILtiDeployment
    {
        $query = Deployment::where('deployment_id', $deploymentId)
            ->whereHas('issuer', function ($q) use ($iss) {
                $q->where('issuer', $iss);
            });

        if ($clientId) {
            $query->whereHas('issuer', function ($q) use ($clientId) {
                $q->where('client_id', $clientId);
            });
        }

        $deployment = $query->first();

        if (! $deployment) {
            return null;
        }

        return LtiDeployment::new($deployment->deployment_id);
    }
}
