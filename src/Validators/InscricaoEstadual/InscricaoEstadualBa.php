<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualBa
{
    public static function validate(string $ie): ValidationResult
    {
        $len = strlen($ie);

        if ($len === 8) {
            $modDigit = $ie[0];
        } elseif ($len === 9) {
            $modDigit = $ie[1];
        } else {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (in_array($modDigit, ['0', '1', '2', '3', '4', '5', '8'], true)) {
            $modulo = 10;
        } elseif (in_array($modDigit, ['6', '7', '9'], true)) {
            $modulo = 11;
        } else {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $baseLen = $len - 2;
        $startWeight = $baseLen + 1;

        // Calcula 2º DV (posição $len - 1)
        $b = $startWeight;
        $soma = 0;

        for ($i = 0; $i < $baseLen; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $r = $soma % $modulo;
        $dv2 = ($modulo === 10)
            ? ($r === 0 ? 0 : $modulo - $r)
            : ($r <= 1   ? 0 : $modulo - $r);

        if ($dv2 !== (int) $ie[$len - 1]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        // Calcula 1º DV (posição $len - 2) usando ie[0..$baseLen-1] + 2º DV × 2
        $b = $startWeight + 1;
        $soma = 0;

        for ($i = 0; $i < $baseLen; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;
        }

        $soma += (int) $ie[$len - 1] * 2;
        $r = $soma % $modulo;
        $dv1 = ($modulo === 10)
            ? ($r === 0 ? 0 : $modulo - $r)
            : ($r <= 1   ? 0 : $modulo - $r);

        if ($dv1 !== (int) $ie[$len - 2]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
