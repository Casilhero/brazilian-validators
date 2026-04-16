<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualRj
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 8) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $b = 2;
        $soma = 0;

        for ($i = 0; $i <= 6; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;

            if ($b === 1) {
                $b = 7;
            }
        }

        $i = $soma % 11;
        $dig = $i <= 1 ? 0 : 11 - $i;

        if ($dig !== (int) $ie[7]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
