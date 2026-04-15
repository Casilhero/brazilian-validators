<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Suframa
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $suframa = Normalizer::digits($value);

        if (strlen($suframa) !== 9) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (str_starts_with($suframa, '00')) {
            return ValidationResult::invalid(ErrorCode::INVALID_PREFIX);
        }

        if (Normalizer::isRepeatedDigits($suframa)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $sum = 0;
        $weight = 9;

        for ($i = 0; $i < 8; $i++) {
            $sum += (int) $suframa[$i] * $weight;
            $weight--;
        }

        $digit = 11 - ($sum % 11);

        if ($digit >= 10) {
            $digit = 0;
        }

        if ((int) $suframa[8] !== $digit) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
