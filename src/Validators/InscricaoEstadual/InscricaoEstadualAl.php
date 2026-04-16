<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualAl
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 9) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (substr($ie, 0, 2) !== '24') {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $b = 9;
        $soma = 0;

        for ($i = 0; $i <= 7; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $soma *= 10;
        $dig = $soma - ((int) ($soma / 11) * 11);

        if ($dig === 10) {
            $dig = 0;
        }

        if ($dig !== (int) $ie[8]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
