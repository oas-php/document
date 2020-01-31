<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#oauth-flows-object
 */
final class OAuthFlows extends Node implements \JsonSerializable
{
    use Serializable;

    protected OAuthFlow $implicit;
    protected OAuthFlow $password;
    protected OAuthFlow $clientCredentials;
    protected OAuthFlow $authorizationCode;

    public function __construct(
        OAuthFlow $implicit,
        OAuthFlow $password,
        OAuthFlow $clientCredentials,
        OAuthFlow $authorizationCode
    ) {
        $this->implicit = $implicit;
        $this->password = $password;
        $this->clientCredentials = $clientCredentials;
        $this->authorizationCode = $authorizationCode;
    }

    public function getImplicit(): OAuthFlow
    {
        return $this->implicit;
    }

    public function getPassword(): OAuthFlow
    {
        return $this->password;
    }

    public function getClientCredentials(): OAuthFlow
    {
        return $this->clientCredentials;
    }

    public function getAuthorizationCode(): OAuthFlow
    {
        return $this->authorizationCode;
    }
}
