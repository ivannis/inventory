<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\String\StringLiteral;

/**
 * @method static static fromNative(string $value)
 */
class Url extends StringLiteral
{
    private SchemeName $scheme;
    private ?string $user;
    private ?string $password;
    private Domain $domain;
    private Path $path;
    private ?Port $port;
    private ?QueryString $queryString;
    private ?FragmentIdentifier $fragmentId;

    public function scheme(): SchemeName
    {
        return $this->scheme;
    }

    public function user(): ?string
    {
        return $this->user;
    }

    public function password(): ?string
    {
        return $this->password;
    }

    public function domain(): Domain
    {
        return $this->domain;
    }

    public function path(): Path
    {
        return $this->path;
    }

    public function port(): ?Port
    {
        return $this->port;
    }

    public function queryString(): ?QueryString
    {
        return $this->queryString;
    }

    public function fragmentId(): ?FragmentIdentifier
    {
        return $this->fragmentId;
    }

    protected function init($value): void
    {
        Assert::url($value);

        $user = \parse_url($value, PHP_URL_USER);
        if ($user) {
            Assert::string($user);
        }
        $this->user = $user ?? null;

        $pass = \parse_url($value, PHP_URL_PASS);
        if ($pass) {
            Assert::string($pass);
        }
        $this->password = $pass ?? null;

        $scheme = \parse_url($value, PHP_URL_SCHEME);
        $this->scheme = SchemeName::fromNative($scheme);

        $host = \parse_url($value, PHP_URL_HOST);
        $this->domain = Domain::create($host);

        $path = \parse_url($value, PHP_URL_PATH);
        $this->path = Path::fromNative($path);

        $port = \parse_url($value, PHP_URL_PORT);
        $this->port = $port ? Port::fromNative($port) : null;

        $queryString = \parse_url($value, PHP_URL_QUERY);
        $this->queryString = $queryString ? QueryString::fromNative(\sprintf('?%s', $queryString)) : null;

        $fragmentId = \parse_url($value, PHP_URL_FRAGMENT);
        $this->fragmentId = $fragmentId ? FragmentIdentifier::fromNative(\sprintf('#%s', $fragmentId)) : null;
    }
}
