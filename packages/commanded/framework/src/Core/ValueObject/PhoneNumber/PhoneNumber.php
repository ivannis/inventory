<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\PhoneNumber;

use Commanded\Core\Assert\Assert;
use Commanded\Core\ValueObject\Geography\CountryCode;
use Commanded\Core\ValueObject\Number\Integer;
use Commanded\Core\ValueObject\String\StringLiteral;
use libphonenumber\PhoneNumber as PhoneNumberInstance;
use libphonenumber\PhoneNumberUtil;

class PhoneNumber extends StringLiteral
{
    private PhoneNumberUtil $phoneNumberUtil;
    private PhoneNumberInstance $phoneNumber;
    protected ?CountryCode $countryCode;
    protected ?Integer $countryPrefix;
    protected ?string $nationalNumber;
    protected PhoneNumberType $numberType;

    public function __construct($value)
    {
        Assert::isArray($value);

        Assert::keyExists($value, 'phoneNumber');
        Assert::keyExists($value, 'countryCode');
        Assert::phoneNumber($value['phoneNumber'], $value['countryCode'], $value['countryCode'] !== null);

        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
        $this->phoneNumber = $this->phoneNumberUtil->parse($value['phoneNumber'], $value['countryCode']);
        $this->nationalNumber = $this->phoneNumber->getNationalNumber();
        $this->numberType = PhoneNumberType::fromNative($this->phoneNumberUtil->getNumberType($this->phoneNumber));

        $countryCode = $this->phoneNumberUtil->getRegionCodeForNumber($this->phoneNumber);
        if ($countryCode !== null && $countryCode !== '001') {
            $this->countryCode = CountryCode::fromNative($countryCode);
        }

        $countryPrefix = $this->phoneNumber->getCountryCode();
        if ($countryPrefix !== null) {
            $this->countryPrefix = Integer::fromNative($countryPrefix);
        }

        if ($value['countryCode'] && $countryCode) {
            Assert::same(
                $value['countryCode'],
                $countryCode,
                \sprintf(
                    'Value "%s" expected to be a valid "%s" phone number.',
                    $this->__toString(),
                    $value['countryCode']
                )
            );
        }

        parent::__construct($this->__toString());
    }

    public function countryCode(): ?CountryCode
    {
        return $this->countryCode ?? null;
    }

    public function countryPrefix(): ?Integer
    {
        return $this->countryPrefix ?? null;
    }

    public function nationalNumber(): ?string
    {
        return $this->nationalNumber ?? null;
    }

    public function numberType(): PhoneNumberType
    {
        return $this->numberType;
    }

    public static function fromNative($value): self
    {
        return new static([
            'phoneNumber' => $value,
            'countryCode' => null,
        ]);
    }

    public static function withCountryCode(string $phoneNumber, CountryCode $countryCode): self
    {
        return new static([
            'phoneNumber' => $phoneNumber,
            'countryCode' => $countryCode->toNative(),
        ]);
    }

    public function format(PhoneNumberFormat $format): string
    {
        return $this->phoneNumberUtil->format($this->phoneNumber, $format->toNative());
    }

    public function formatForCountry(CountryCode $countryCode): string
    {
        return $this->phoneNumberUtil->formatOutOfCountryCallingNumber(
            $this->phoneNumber,
            $countryCode->toNative()
        );
    }

    public function formatForMobileDialingInCountry(CountryCode $countryCode): string
    {
        return $this->phoneNumberUtil->formatNumberForMobileDialing(
            $this->phoneNumber,
            $countryCode->toNative(),
            false
        );
    }

    public function toE164String(): string
    {
        return $this->format(PhoneNumberFormat::E164());
    }

    public function toInternationalString(): string
    {
        return $this->format(PhoneNumberFormat::INTERNATIONAL());
    }

    public function toNationalString(): string
    {
        return $this->format(PhoneNumberFormat::NATIONAL());
    }

    public function toRfc3966String(): string
    {
        return $this->format(PhoneNumberFormat::RFC3966());
    }

    public function __toString(): string
    {
        return $this->toE164String();
    }
}
