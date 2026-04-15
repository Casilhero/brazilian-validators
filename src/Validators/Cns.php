<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cns
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $cns = Normalizer::digits($value);

        if (strlen($cns) !== 15) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (Normalizer::isRepeatedDigits($cns)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        if (! in_array($cns[0], ['1', '2', '7', '8', '9'], true)) {
            return ValidationResult::invalid(ErrorCode::INVALID_PREFIX);
        }

        $sum = 0;

        for ($i = 0; $i < 15; $i++) {
            $sum += (int) $cns[$i] * (15 - $i);
        }

        if ($sum % 11 !== 0) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
