<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cpf implements BrazilianValidatorContract
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

    public static function generate(): string
    {
        do {
            $digits = [];
            for ($i = 0; $i < 9; $i++) {
                $digits[] = random_int(0, 9);
            }
        } while (Normalizer::isRepeatedDigits(implode('', $digits)));

        // DV1 (position 9)
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $digits[$i] * (10 - $i);
        }
        $dv1 = (($sum * 10) % 11) % 10;
        $digits[] = $dv1;

        // DV2 (position 10)
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $digits[$i] * (11 - $i);
        }
        $dv2 = (($sum * 10) % 11) % 10;
        $digits[] = $dv2;

        return implode('', $digits);
    }

    public static function mask(string $value): string
    {
        $d = Normalizer::digits($value);

        return substr($d, 0, 3) . '.' . substr($d, 3, 3) . '.' . substr($d, 6, 3) . '-' . substr($d, 9, 2);
    }
}
