<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class PhoneDdi
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
}
