<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cpf
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $cpf = Normalizer::digits($value);

        if (strlen($cpf) !== 11) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (Normalizer::isRepeatedDigits($cpf)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        for ($position = 9; $position <= 10; $position++) {
            $sum = 0;

            for ($i = 0; $i < $position; $i++) {
                $sum += (int) $cpf[$i] * (($position + 1) - $i);
            }

            $digit = (($sum * 10) % 11) % 10;

            if ((int) $cpf[$position] !== $digit) {
                return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
            }
        }

        return ValidationResult::valid();
    }
}
