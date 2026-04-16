<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class PhoneDdi implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $phone = Normalizer::digits($value);

        if (! str_starts_with($phone, '55')) {
            return ValidationResult::invalid(ErrorCode::INVALID_PREFIX);
        }

        $nationalDigits = substr($phone, 2);

        return Phone::validateNationalDigits($nationalDigits);
    }

    public static function generate(): string
    {
        return '55' . Phone::generate();
    }

    public static function mask(string $value): string
    {
        $d          = Normalizer::digits($value);
        $ddd        = substr($d, 2, 2);
        $subscriber = substr($d, 4);

        if (strlen($subscriber) === 9) {
            return '+55 (' . $ddd . ') ' . substr($subscriber, 0, 5) . '-' . substr($subscriber, 5);
        }

        return '+55 (' . $ddd . ') ' . substr($subscriber, 0, 4) . '-' . substr($subscriber, 4);
    }
}
