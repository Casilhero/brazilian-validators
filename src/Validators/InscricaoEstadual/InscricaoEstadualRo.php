<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualRo
{
    public static function validate(string $ie): ValidationResult
    {
        $len = strlen($ie);

        if ($len === 9) {
            $b = 6;
            $soma = 0;

            for ($i = 3; $i <= 7; $i++) {
                $soma += (int) $ie[$i] * $b;
                $b--;
            }

            $dig = 11 - ($soma % 11);

            if ($dig >= 10) {
                $dig -= 10;
            }

            if ($dig !== (int) $ie[8]) {
                return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
            }

            return ValidationResult::valid();
        }

        if ($len === 14) {
            $b = 6;
            $soma = 0;

            for ($i = 0; $i <= 12; $i++) {
                $soma += (int) $ie[$i] * $b;
                $b--;

                if ($b === 1) {
                    $b = 9;
                }
            }

            $dig = 11 - ($soma % 11);

            if ($dig >= 10) {
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
