<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\Intl;

use Commanded\Core\ValueObject\ValueObject;

interface LocalizableValue extends ValueObject
{
    const DEFAULT_LOCALE = Locale::__DEFAULT;
    const DEFAULT_MODE = LocalizableValueMode::__DEFAULT;

    public function mode(): LocalizableValueMode;

    public function locale(): Locale;

    public function withLocale(Locale $locale): LocalizableValue;

    public function remove(Locale $locale): LocalizableValue;

    public function has(Locale $locale): bool;

    /**
     * @return mixed
     */
    public function translate(Locale $locale);

    public function translation(Locale $locale);
}
