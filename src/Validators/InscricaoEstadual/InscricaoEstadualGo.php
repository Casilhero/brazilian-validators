<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualGo
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 9) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $prefix2 = substr($ie, 0, 2);
        $prefix2Int = (int) $prefix2;

        if (!in_array($prefix2, ['10', '11'], true) && !($prefix2Int >= 20 && $prefix2Int <= 29)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $b = 9;
        $soma = 0;

        for ($i = 0; $i <= 7; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $i = $soma % 11;
        $dig = $i <= 1 ? 0 : 11 - $i;

        if ($dig !== (int) $ie[8]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
