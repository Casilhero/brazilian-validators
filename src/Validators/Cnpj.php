<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cnpj
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $cnpj = Normalizer::digits($value);

        if (strlen($cnpj) !== 14) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (Normalizer::isRepeatedDigits($cnpj)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $sum = 0;
        $weight = 5;

        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $cnpj[$i] * $weight;
            $weight = $weight === 2 ? 9 : $weight - 1;
        }

        $rest = $sum % 11;
        $digit1 = $rest < 2 ? 0 : 11 - $rest;

        if ((int) $cnpj[12] !== $digit1) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        $sum = 0;
        $weight = 6;

        for ($i = 0; $i < 13; $i++) {
            $sum += (int) $cnpj[$i] * $weight;
            $weight = $weight === 2 ? 9 : $weight - 1;
        }

        $rest = $sum % 11;
        $digit2 = $rest < 2 ? 0 : 11 - $rest;

        if ((int) $cnpj[13] !== $digit2) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
