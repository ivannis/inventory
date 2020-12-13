<?php

declare(strict_types=1);

namespace Commanded\Core\ValueObject\PhoneNumber;

use Commanded\Core\ValueObject\Enum\Enum;

/**
 * @method static PhoneNumberType LANDLINE();
 * @method static PhoneNumberType MOBILE();
 * @method static PhoneNumberType LANDLINE_OR_MOBILE();
 * @method static PhoneNumberType TOLL_FREE();
 * @method static PhoneNumberType PREMIUM_RATE();
 * @method static PhoneNumberType SHARED_COST();
 * @method static PhoneNumberType VOIP();
 * @method static PhoneNumberType PERSONAL_NUMBER();
 * @method static PhoneNumberType PAGER();
 * @method static PhoneNumberType UAN();
 * @method static PhoneNumberType UNKNOWN();
 * @method static PhoneNumberType EMERGENCY();
 * @method static PhoneNumberType VOICEMAIL();
 * @method static PhoneNumberType SHORT_CODE();
 * @method static PhoneNumberType STANDARD_RATE();
 * @method static static fromNative(string $value)
 * @method string toNative()
 */
final class PhoneNumberType extends Enum
{
    /**
     * Fixed line number.
     */
    private const LANDLINE = 0;

    /**
     * Mobile number.
     */
    private const MOBILE = 1;

    /**
     * Fixed line or mobile number.
     *
     * In some regions (e.g. the USA), it is impossible to distinguish between fixed-line and
     * mobile numbers by looking at the phone number itself.
     */
    private const LANDLINE_OR_MOBILE = 2;

    /**
     * Freephone number.
     */
    private const TOLL_FREE = 3;

    /**
     * Premium rate number.
     */
    private const PREMIUM_RATE = 4;

    /**
     * Shared cost number.
     *
     * The cost of this call is shared between the caller and the recipient, and is hence typically
     * less than PREMIUM_RATE calls.
     *
     * @see http://en.wikipedia.org/wiki/Shared_Cost_Service
     */
    private const SHARED_COST = 5;

    /**
     * Voice over IP number.
     *
     * This includes TSoIP (Telephony Service over IP).
     */
    private const VOIP = 6;

    /**
     * Personal number.
     *
     * A personal number is associated with a particular person, and may be routed to either a
     * MOBILE or LANDLINE number.
     *
     * @see http://en.wikipedia.org/wiki/Personal_Numbers
     */
    private const PERSONAL_NUMBER = 7;

    /**
     * Pager number.
     */
    private const PAGER = 8;

    /**
     * Universal Access Number or Company Number.
     *
     * The number may be further routed to specific offices, but allows one number to be used for a company.
     */
    private const UAN = 9;

    /**
     * Unknown number type.
     *
     * A phone number is of type UNKNOWN when it does not fit any of the known patterns
     * for a specific region.
     */
    private const UNKNOWN = 10;

    /**
     * Emergency number.
     */
    private const EMERGENCY = 27;

    /**
     * Voicemail number.
     */
    private const VOICEMAIL = 28;

    /**
     * Short code number.
     */
    private const SHORT_CODE = 29;

    /**
     * Standard rate number.
     */
    private const STANDARD_RATE = 30;
}
