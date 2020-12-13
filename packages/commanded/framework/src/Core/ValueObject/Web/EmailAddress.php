<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Web;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\String\StringLiteral;

/**
 * @method static static fromNative(string $value)
 */
class EmailAddress extends StringLiteral
{
    private string$user;
    private HostName $domain;

    public function user(): string
    {
        return $this->user;
    }

    public function domain(): HostName
    {
        return $this->domain;
    }

    protected function init($value): void
    {
        Assert::email($value);

        $parts = explode('@', $value);
        Assert::string($parts[0]);

        $this->user = $parts[0];
        $domain = \trim($parts[1], '[]');
        $this->domain = HostName::create($domain);
    }
}
