<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualPr
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 10) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $b = 3;
        $soma = 0;

        for ($i = 0; $i <= 7; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;

            if ($b === 1) {
                $b = 7;
            }
        }

        $i = $soma % 11;
        $dig = $i <= 1 ? 0 : 11 - $i;

        if ($dig !== (int) $ie[8]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        $b = 4;
        $soma = 0;

        for ($i = 0; $i <= 8; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;

            if ($b === 1) {
                $b = 7;
            }
        }

        $i = $soma % 11;
        $dig = $i <= 1 ? 0 : 11 - $i;

        if ($dig !== (int) $ie[9]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
