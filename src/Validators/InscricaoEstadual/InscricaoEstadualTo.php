<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualTo
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 11) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $s = substr($ie, 2, 2);

        if (!in_array($s, ['01', '02', '03', '99'], true)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $b = 9;
        $soma = 0;

        for ($i = 0; $i <= 9; $i++) {
            if ($i === 2 || $i === 3) {
                continue;
            }

            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $i = $soma % 11;
        $dig = $i < 2 ? 0 : 11 - $i;

        if ($dig !== (int) $ie[10]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
