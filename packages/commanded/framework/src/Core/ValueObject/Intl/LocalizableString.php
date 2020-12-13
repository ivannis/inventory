<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Intl;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\AbstractValueObject;

/**
 * @method static static fromNative(array $value)
 * @method array toNative()
 */
class LocalizableString extends AbstractValueObject implements LocalizableValue
{
    use LocalizableValueTrait;

    public function add(string $value, Locale $locale): self
    {
        return $this->withTranslation($value, $locale);
    }

    public function translation(Locale $locale): ?string
    {
        $translation = $this->getTranslation($locale);

        return $translation ?? null;
    }

    protected function assert($translation): void
    {
        Assert::string($translation);
    }
}
