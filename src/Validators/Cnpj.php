<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Cnpj implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $cnpj = Normalizer::cnpjAlphanumeric($value);

        if (strlen($cnpj) !== 14) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (! preg_match('/^[A-Z0-9]{12}[0-9]{2}$/', $cnpj)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        if (preg_match('/^(.)\1{13}$/', $cnpj)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $sum = 0;
        $weight = 5;

        for ($i = 0; $i < 12; $i++) {
            $sum += (ord($cnpj[$i]) - 48) * $weight;
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
            $sum += (ord($cnpj[$i]) - 48) * $weight;
            $weight = $weight === 2 ? 9 : $weight - 1;
        }

        $rest = $sum % 11;
        $digit2 = $rest < 2 ? 0 : 11 - $rest;

        if ((int) $cnpj[13] !== $digit2) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        do {
            $chars = [];
            for ($i = 0; $i < 12; $i++) {
                $chars[] = (string) random_int(0, 9);
            }
        } while (count(array_unique($chars)) === 1);

        $cnpj = implode('', $chars);

        // DV1
        $sum = 0;
        $weight = 5;
        for ($i = 0; $i < 12; $i++) {
            $sum += (ord($cnpj[$i]) - 48) * $weight;
            $weight = $weight === 2 ? 9 : $weight - 1;
        }
        $rest = $sum % 11;
        $dv1 = $rest < 2 ? 0 : 11 - $rest;

        $cnpj .= $dv1;

        // DV2
        $sum = 0;
        $weight = 6;
        for ($i = 0; $i < 13; $i++) {
            $sum += (ord($cnpj[$i]) - 48) * $weight;
            $weight = $weight === 2 ? 9 : $weight - 1;
        }
        $rest = $sum % 11;
        $dv2 = $rest < 2 ? 0 : 11 - $rest;

        return $cnpj . $dv2;
    }

    public static function mask(string $value): string
    {
        $d = Normalizer::cnpjAlphanumeric($value);

        return substr($d, 0, 2) . '.' . substr($d, 2, 3) . '.' . substr($d, 5, 3) . '/' . substr($d, 8, 4) . '-' . substr($d, 12, 2);
    }
}
