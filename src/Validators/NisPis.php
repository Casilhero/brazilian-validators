<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class NisPis implements BrazilianValidatorContract
{
    /**
     * @var int[]
     */
    private const WEIGHTS = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $nis = Normalizer::digits($value);

        if (strlen($nis) !== 11) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (Normalizer::isRepeatedDigits($nis)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += (int) $nis[$i] * self::WEIGHTS[$i];
        }

        $remainder = 11 - ($sum % 11);
        $digit = $remainder >= 10 ? 0 : $remainder;

        if ((int) $nis[10] !== $digit) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        do {
            $digits = [];
            for ($i = 0; $i < 10; $i++) {
                $digits[] = random_int(0, 9);
            }
        } while (Normalizer::isRepeatedDigits(implode('', $digits)));

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $digits[$i] * self::WEIGHTS[$i];
        }
        $remainder = 11 - ($sum % 11);
        $dv = $remainder >= 10 ? 0 : $remainder;
        $digits[] = $dv;

        return implode('', $digits);
    }

    public static function mask(string $value): string
    {
        $d = Normalizer::digits($value);

        return substr($d, 0, 3) . '.' . substr($d, 3, 5) . '.' . substr($d, 8, 2) . '-' . $d[10];
    }
}
