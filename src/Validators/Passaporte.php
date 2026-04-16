<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

/**
 * Validação de Passaporte Brasileiro.
 *
 * Formato: 2 letras seguidas de 6 dígitos (ex: AB123456).
 * Não possui dígito verificador — validação de formato apenas.
 */
final class Passaporte implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        if (! preg_match('/^[A-Za-z]{2}\d{6}$/', $value)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $letter1 = $letters[random_int(0, 25)];
        $letter2 = $letters[random_int(0, 25)];
        $digits  = '';
        for ($i = 0; $i < 6; $i++) {
            $digits .= (string) random_int(0, 9);
        }

        return $letter1 . $letter2 . $digits;
    }

    public static function mask(string $value): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $value) ?? $value);
    }
}
