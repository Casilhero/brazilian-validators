<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class CpfCnpj
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $digits = Normalizer::digits($value);
        $length = strlen($digits);

        if ($length === 11) {
            return Cpf::validate($digits);
        }

        if ($length === 14) {
            return Cnpj::validate($digits);
        }

        return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
    }
}
