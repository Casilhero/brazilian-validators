<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\BrazilianAreaCodes;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Phone implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $phone = Normalizer::digits($value);

        if (str_starts_with($phone, '55') && (strlen($phone) === 12 || strlen($phone) === 13)) {
            return ValidationResult::invalid(ErrorCode::INVALID_PREFIX);
        }

        return self::validateNationalDigits($phone);
    }

    public static function validateNationalDigits(string $phone): ValidationResult
    {
        $length = strlen($phone);

        if ($length !== 10 && $length !== 11) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (Normalizer::isRepeatedDigits($phone)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $ddd = substr($phone, 0, 2);

        if (! BrazilianAreaCodes::isValid($ddd)) {
            return ValidationResult::invalid(ErrorCode::INVALID_REGION);
        }

        $subscriber = substr($phone, 2);
        $subscriberLength = strlen($subscriber);

        if ($subscriberLength === 9 && $subscriber[0] !== '9') {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        if ($subscriberLength === 8 && ! preg_match('/^[2-8]/', $subscriber)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        $ddds = BrazilianAreaCodes::all();
        $ddd  = $ddds[array_rand($ddds)];

        $subscriber = '9';
        for ($i = 0; $i < 8; $i++) {
            $subscriber .= (string) random_int(0, 9);
        }

        return $ddd . $subscriber;
    }

    public static function mask(string $value): string
    {
        $d          = Normalizer::digits($value);
        $ddd        = substr($d, 0, 2);
        $subscriber = substr($d, 2);

        if (strlen($subscriber) === 9) {
            return '(' . $ddd . ') ' . substr($subscriber, 0, 5) . '-' . substr($subscriber, 5);
        }

        return '(' . $ddd . ') ' . substr($subscriber, 0, 4) . '-' . substr($subscriber, 4);
    }
}
