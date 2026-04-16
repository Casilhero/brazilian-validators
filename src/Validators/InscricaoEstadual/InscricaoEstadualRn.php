<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualRn
{
    public static function validate(string $ie): ValidationResult
    {
        $len = strlen($ie);

        if ($len !== 9 && $len !== 10) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (substr($ie, 0, 2) !== '20') {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $b = $len;
        $s = $len === 9 ? 7 : 8;
        $soma = 0;

        for ($i = 0; $i <= $s; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $soma *= 10;
        $dig = $soma % 11;

        if ($dig === 10) {
            $dig = 0;
        }

        if ($dig !== (int) $ie[$s + 1]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
