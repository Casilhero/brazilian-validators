<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cnh
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $cnh = Normalizer::digits($value);

        if (strlen($cnh) !== 11) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (Normalizer::isRepeatedDigits($cnh)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $base = substr($cnh, 0, 9);

        $sum = 0;
        $weight = 9;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $base[$i] * $weight;
            $weight--;
        }

        $firstDigit = $sum % 11;
        $discount = 0;

        if ($firstDigit >= 10) {
            $firstDigit = 0;
            $discount = 2;
        }

        $sum = 0;

        for ($i = 0, $weight = 1; $i < 9; $i++, $weight++) {
            $sum += (int) $base[$i] * $weight;
        }

        $secondDigit = ($sum % 11) - $discount;

        if ($secondDigit < 0) {
            $secondDigit += 11;
        }

        if ($secondDigit >= 10) {
            $secondDigit = 0;
        }

        if ((int) $cnh[9] !== $firstDigit || (int) $cnh[10] !== $secondDigit) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
