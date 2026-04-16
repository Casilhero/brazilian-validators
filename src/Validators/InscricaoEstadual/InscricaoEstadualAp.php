<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualAp
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 9) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (substr($ie, 0, 2) !== '03') {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $prefix = (int) substr($ie, 0, 8);

        if ($prefix >= 3000001 && $prefix <= 3017000) {
            $p = 5;
            $d = 0;
        } elseif ($prefix >= 3017001 && $prefix <= 3019022) {
            $p = 9;
            $d = 1;
        } else {
            $p = 0;
            $d = 0;
        }

        $b = 9;
        $soma = $p;

        for ($i = 0; $i <= 7; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $dig = 11 - ($soma % 11);

        if ($dig === 10) {
            $dig = 0;
        } elseif ($dig === 11) {
            $dig = $d;
        }

        if ($dig !== (int) $ie[8]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
