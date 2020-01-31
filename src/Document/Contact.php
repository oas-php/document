<?php declare(strict_types=1);

namespace OAS\Document;

use OAS\Utils\Node;
use OAS\Utils\Serializable;

/**
 * @see https://spec.openapis.org/oas/v3.1.0#contact-object
 */
final class Contact extends Node implements \JsonSerializable
{
    use Serializable;

    protected ?string $name;

    protected ?string $url;

    protected ?string $email;

    public function __construct(string $name = null, string $url = null, string $email = null)
    {
        $this->name = $name;
        $this->url = $url;
        $this->email = $email;
    }

    public function hasName(): bool
    {
        return !is_null($this->name);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function hasUrl(): bool
    {
        return !is_null($this->url);
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function hasEmail(): bool
    {
        return !is_null($this->email);
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
