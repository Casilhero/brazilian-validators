<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cnh implements BrazilianValidatorContract
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

    public static function generate(): string
    {
        do {
            $digits = [];
            for ($i = 0; $i < 9; $i++) {
                $digits[] = random_int(0, 9);
            }
        } while (Normalizer::isRepeatedDigits(implode('', $digits)));

        // DV1
        $sum = 0;
        $weight = 9;
        for ($i = 0; $i < 9; $i++) {
            $sum += $digits[$i] * $weight;
            $weight--;
        }
        $firstDigit = $sum % 11;
        $discount = 0;
        if ($firstDigit >= 10) {
            $firstDigit = 0;
            $discount = 2;
        }

        // DV2
        $sum = 0;
        for ($i = 0, $w = 1; $i < 9; $i++, $w++) {
            $sum += $digits[$i] * $w;
        }
        $secondDigit = ($sum % 11) - $discount;
        if ($secondDigit < 0) {
            $secondDigit += 11;
        }
        if ($secondDigit >= 10) {
            $secondDigit = 0;
        }

        return implode('', $digits) . $firstDigit . $secondDigit;
    }

    public static function mask(string $value): string
    {
        return Normalizer::digits($value);
    }
}
