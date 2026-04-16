<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualPe
{
    public static function validate(string $ie): ValidationResult
    {
        $len = strlen($ie);

        if ($len === 9) {
            $b = 8;
            $soma = 0;

            for ($i = 0; $i <= 6; $i++) {
                $soma += (int) $ie[$i] * $b;
                $b--;
            }

            $i = $soma % 11;
            $dig = $i <= 1 ? 0 : 11 - $i;

            if ($dig !== (int) $ie[7]) {
                return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
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

        if ($len === 14) {
            $b = 5;
            $soma = 0;

            for ($i = 0; $i <= 12; $i++) {
                $soma += (int) $ie[$i] * $b;
                $b--;

                if ($b === 0) {
                    $b = 9;
                }
            }

            $dig = 11 - ($soma % 11);

            if ($dig > 9) {
                $dig -= 10;
            }

            if ($dig !== (int) $ie[13]) {
                return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
            }

            return ValidationResult::valid();
        }

        return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
    }
}
