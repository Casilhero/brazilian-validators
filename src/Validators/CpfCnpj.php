<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class CpfCnpj implements BrazilianValidatorContract
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

        $cnpj = Normalizer::cnpjAlphanumeric($value);

        if (strlen($cnpj) === 14) {
            return Cnpj::validate($cnpj);
        }

        return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
    }

    public static function generate(): string
    {
        return random_int(0, 1) === 0 ? Cpf::generate() : Cnpj::generate();
    }

    public static function mask(string $value): string
    {
        $digits = Normalizer::digits($value);

        if (strlen($digits) === 11) {
            return Cpf::mask($value);
        }

        return Cnpj::mask($value);
    }
}
