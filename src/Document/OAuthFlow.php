<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#oauth-flow-object
 */
final class OAuthFlow extends Node implements \JsonSerializable
{
    use Serializable;

    protected string $authorizationUrl;

    protected string $tokenUrl;

    protected array $scopes;

    protected ?string $refreshUrl;

    public function __construct(
        string $authorizationUrl,
        string $tokenUrl,
        array $scopes,
        string $refreshUrl = null
    ) {
        $this->authorizationUrl = $authorizationUrl;
        $this->tokenUrl = $tokenUrl;
        $this->scopes = $scopes;
        $this->refreshUrl = $refreshUrl;
    }

    public function getAuthorizationUrl(): string
    {
        return $this->authorizationUrl;
    }

    public function getTokenUrl(): string
    {
        return $this->tokenUrl;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function hasRefreshUrl(): bool
    {
        return !is_null($this->refreshUrl);
    }

    public function getRefreshUrl(): ?string
    {
        return $this->refreshUrl;
    }
}
