<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cns implements BrazilianValidatorContract
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

    public static function generate(): string
    {
        while (true) {
            $digits = [(string) random_int(7, 9)];
            for ($i = 1; $i < 14; $i++) {
                $digits[] = (string) random_int(0, 9);
            }

            $sum = 0;
            for ($i = 0; $i < 14; $i++) {
                $sum += (int) $digits[$i] * (15 - $i);
            }

            $lastDigit = (11 - ($sum % 11)) % 11;

            if ($lastDigit > 9) {
                continue;
            }

            $digits[] = (string) $lastDigit;
            $cns = implode('', $digits);

            if (! Normalizer::isRepeatedDigits($cns)) {
                return $cns;
            }
        }
    }

    public static function mask(string $value): string
    {
        $d = Normalizer::digits($value);

        return substr($d, 0, 3) . ' ' . substr($d, 3, 4) . ' ' . substr($d, 7, 4) . ' ' . substr($d, 11, 4);
    }
}
