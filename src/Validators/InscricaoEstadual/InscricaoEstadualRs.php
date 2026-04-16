<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualRs
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 10) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $b = 2;
        $soma = 0;

        for ($i = 0; $i <= 8; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;

            if ($b === 1) {
                $b = 9;
            }
        }

        $dig = 11 - ($soma % 11);

        if ($dig >= 10) {
            $dig = 0;
        }

        if ($dig !== (int) $ie[9]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
