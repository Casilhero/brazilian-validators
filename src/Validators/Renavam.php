<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Renavam implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $renavam = Normalizer::digits($value);

        if (strlen($renavam) !== 11) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $weights = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $renavam[$i] * $weights[$i];
        }

        $digit = ($sum * 10) % 11;

        if ($digit === 10) {
            $digit = 0;
        }

        if ((int) $renavam[10] !== $digit) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        $weights = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $digits  = [];
        for ($i = 0; $i < 10; $i++) {
            $digits[] = random_int(0, 9);
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $digits[$i] * $weights[$i];
        }
        $digit = ($sum * 10) % 11;
        if ($digit === 10) {
            $digit = 0;
        }
        $digits[] = $digit;

        return implode('', $digits);
    }

    public static function mask(string $value): string
    {
        return Normalizer::digits($value);
    }
}
