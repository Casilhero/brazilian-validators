<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualAc
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 13) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (substr($ie, 0, 2) !== '01') {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $b = 4;
        $soma = 0;

        for ($i = 0; $i <= 10; $i++) {
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

        if ($dig !== (int) $ie[11]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        $b = 5;
        $soma = 0;

        for ($i = 0; $i <= 11; $i++) {
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

        if ($dig !== (int) $ie[12]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
