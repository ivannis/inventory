<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Intl;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\ValueObject;

/**
 * @method array toNative()
 * @method void assert($translation)
 * @method ValueObject translation(Locale $locale)
 */
trait LocalizableValueTrait
{
    private Locale $locale;
    private LocalizableValueMode $mode;

    public function __toString(): string
    {
        $translation = $this->translation($this->locale());

        return $translation === null ? '' : $translation->__toString();
    }

    public function mode(): LocalizableValueMode
    {
        return $this->mode;
    }

    public function locale(): Locale
    {
        return $this->resolveLocale($this->locale, $this->mode);
    }

    public function withLocale(Locale $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function has(Locale $locale): bool
    {
        $translations = $this->toNative();

        return isset($translations[$locale->toNative()]);
    }

    public function remove(Locale $locale): LocalizableValue
    {
        $translations = $this->toNative();
        unset($translations[$locale->toNative()]);

        return new self($translations);
    }

    public function translate(Locale $locale, LocalizableValueMode $mode = null)
    {
        $locale = $this->resolveLocale($locale, $mode === null ? LocalizableValueMode::STRICT() : $mode);

        return $this->getTranslation($locale);
    }

    protected function init($value): void
    {
        Assert::isArray($value);
        foreach ($value as $locale => $translation) {
            Assert::oneOf($locale, Locale::toArray());

            $this->assert($translation);
        }

        $defaultLocale = $value['default_locale'] ?? LocalizableValue::DEFAULT_LOCALE;
        $defaultMode = $value['default_mode'] ?? LocalizableValue::DEFAULT_MODE;

        $this->locale = Locale::fromNative($defaultLocale);
        $this->mode = LocalizableValueMode::fromNative($defaultMode);
    }

    private function withTranslation($value, Locale $locale): self
    {
        Assert::scalar($value);

        $translations = $this->toNative();
        $translations[$locale->toNative()] = $value;

        return new self($translations);
    }

    private function getTranslation(Locale $locale)
    {
        $translations = $this->toNative();

        return $translations[$locale->toNative()] ?? null;
    }

    private function resolveLocale(Locale $locale, LocalizableValueMode $mode): Locale
    {
        if ($mode == LocalizableValueMode::STRICT()) {
            return $locale;
        }

        if ($mode == LocalizableValueMode::ANY()) {
            if ($this->has($locale)) {
                return $locale;
            }

            if ($this->has(Locale::fromNative(LocalizableValue::DEFAULT_LOCALE))) {
                return Locale::fromNative(LocalizableValue::DEFAULT_LOCALE);
            }

            if (count($this->translations) > 0) {
                return Locale::fromNative(array_keys($this->translations)[0]);
            }
        }

        return $locale;
    }
}
