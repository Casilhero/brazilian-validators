<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualSp
{
    public static function validate(string $ie): ValidationResult
    {
        if (str_starts_with($ie, 'P')) {
            if (strlen($ie) !== 13) {
                return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
            }

            $b = 1;
            $soma = 0;

            for ($i = 1; $i <= 8; $i++) {
                $soma += (int) $ie[$i] * $b;
                $b++;

                if ($b === 2) {
                    $b = 3;
                }

                if ($b === 9) {
                    $b = 10;
                }
            }

            $dig = $soma % 11;

            if ($dig > 9) {
                $dig = 0;
            }

            if ($dig !== (int) $ie[9]) {
                return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
            }

            return ValidationResult::valid();
        }

        if (strlen($ie) !== 12) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $b = 1;
        $soma = 0;

        for ($i = 0; $i <= 7; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b++;

            if ($b === 2) {
                $b = 3;
            }

            if ($b === 9) {
                $b = 10;
            }
        }

        $dig = $soma % 11;

        if ($dig > 9) {
            $dig = 0;
        }

        if ($dig !== (int) $ie[8]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        $b = 3;
        $soma = 0;

        for ($i = 0; $i <= 10; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;

            if ($b === 1) {
                $b = 10;
            }
        }

        $dig = $soma % 11;

        if ($dig > 9) {
            $dig = 0;
        }

        if ($dig !== (int) $ie[11]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
