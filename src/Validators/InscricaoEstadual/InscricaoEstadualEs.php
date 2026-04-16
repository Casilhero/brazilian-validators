<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualEs
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 9) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $b = 9;
        $soma = 0;

        for ($i = 0; $i <= 7; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $i = $soma % 11;
        $dig = $i < 2 ? 0 : 11 - $i;

        if ($dig !== (int) $ie[8]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
