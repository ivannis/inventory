<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Intl;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;
use Commanded\Core\ValueObject\Web\Domain;
use Commanded\Core\ValueObject\Web\FragmentIdentifier;
use Commanded\Core\ValueObject\Web\Path;
use Commanded\Core\ValueObject\Web\Port;
use Commanded\Core\ValueObject\Web\QueryString;
use Commanded\Core\ValueObject\Web\SchemeName;
use Commanded\Core\ValueObject\Web\Url;

/**
 * @method static static fromNative(array $value)
 * @method array toNative()
 */
class LocalizableUrl extends AbstractValueObject implements LocalizableValue
{
    use LocalizableValueTrait;

    public function add(Url $value, Locale $locale): self
    {
        return $this->withTranslation($value->toNative(), $locale);
    }

    public function translation(Locale $locale): ?Url
    {
        $translation = $this->getTranslation($locale);

        return $translation ? Url::fromNative($translation) : null;
    }

    public function scheme(): ?SchemeName
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->scheme() : null;
    }

    public function user(): ?string
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->user() : null;
    }

    public function password(): ?string
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->password() : null;
    }

    public function domain(): ?Domain
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->domain() : null;
    }

    public function path(): ?Path
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->path() : null;
    }

    public function port(): ?Port
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->port() : null;
    }

    public function queryString(): ?QueryString
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->queryString() : null;
    }

    public function fragmentId(): ?FragmentIdentifier
    {
        $translation = $this->translation($this->locale());

        return $translation ? $translation->fragmentId() : null;
    }

    protected function assert($translation): void
    {
        Assert::url($translation);
    }
}
