<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * TODO: verify required params
 *
 * @see https://spec.openapis.org/oas/v3.1.0#security-scheme-object
 */
final class SecurityScheme extends Node implements \JsonSerializable
{
    use Serializable;

    const TYPE = [
        self::TYPE_API_KEY,
        self::TYPE_HTTP,
        self::TYPE_MUTUAL_TLS,
        self::TYPE_OAUTH2,
        self::TYPE_OPEN_ID_CONNECT
    ];

    const IN = [
        self::IN_QUERY,
        self::IN_HEADER,
        self::IN_COOKIE
    ];

    const TYPE_API_KEY = 'apiKey';
    const TYPE_HTTP = 'http';
    const TYPE_MUTUAL_TLS = 'mutualTLS';
    const TYPE_OAUTH2 = 'oauth2';
    const TYPE_OPEN_ID_CONNECT = 'openIdConnect';

    const IN_QUERY = 'query';
    const IN_HEADER ='header';
    const IN_COOKIE ='cookie';

    protected string $type;
    protected ?string $name;
    protected ?string $in;
    protected ?string $scheme;
    protected ?string $description;
    protected ?string $bearerFormat;
    protected ?OAuthFlows $flows;
    protected ?string $openIdConnectUrl;

    public function __construct(
        string $type,
        string $name = null,
        string $in = null,
        string $scheme = null,
        string $description = null,
        string $bearerFormat = null,
        OAuthFlows $flows = null,
        string $openIdConnectUrl = null
    ) {
        $this->setType($type);
        $this->setName($name);
        $this->setIn($in);
        $this->setScheme($scheme);
        $this->description = $description;
        $this->bearerFormat = $bearerFormat;
        $this->setFlows($flows);
        $this->setOpenIdConnectUrl($openIdConnectUrl);
    }

    private function setType(string $type): void
    {
        if (!in_array($type, self::TYPE)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid value for "type" parameter: %s. Valid options are: %s',
                    $type,
                    join(', ', self::TYPE)
                )
            );
        }

        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    private function setName(?string $name): void
    {
        if (is_null($name) && self::TYPE_API_KEY === $this->type) {
            throw new \InvalidArgumentException(
                'Parameter "name" must not be null when "type" is "apiKey"'
            );
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function setIn(?string $in): void
    {
        if (is_null($in) && self::TYPE_API_KEY === $this->type) {
            throw new \InvalidArgumentException(
                'Parameter "in" must not be null when "type" is "apiKey"'
            );
        }

        if (!in_array($in, self::IN)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid value for "in" parameter: %s. Valid options are: %s',
                    $in,
                    join(', ', self::IN)
                )
            );
        }

        $this->in = $in;
    }

    public function getIn(): string
    {
        return $this->in;
    }

    private function setScheme(?string $scheme): void
    {
        if (is_null($scheme) && self::TYPE_HTTP === $this->type) {
            throw new \InvalidArgumentException(
                'Parameter "scheme" must not be null when "type" is "http"'
            );
        }

        $this->scheme = $scheme;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    private function setFlows(?OAuthFlows $flows): void
    {
        if (is_null($flows) && self::TYPE_OAUTH2 === $this->type) {
            throw new \InvalidArgumentException(
                'Parameter "flows" must not be null when "type" is "oauth2"'
            );
        }

        $this->flows = $flows;
    }

    public function getFlows(): OAuthFlows
    {
        return $this->flows;
    }

    private function setOpenIdConnectUrl(?string $openIdConnectUrl): void
    {
        if (is_null($openIdConnectUrl) && self::TYPE_OPEN_ID_CONNECT === $this->type) {
            throw new \InvalidArgumentException(
                'Parameter "openIdConnectUrl" must not be null when "type" is "openIdConnect"'
            );
        }

        $this->openIdConnectUrl = $openIdConnectUrl;
    }

    public function getOpenIdConnectUrl(): string
    {
        return $this->openIdConnectUrl;
    }

    public function hasDescription(): bool
    {
        return !is_null($this->description);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function hasBearerFormat(): bool
    {
        return !is_null($this->bearerFormat);
    }

    public function getBearerFormat(): ?string
    {
        return $this->bearerFormat;
    }
}
