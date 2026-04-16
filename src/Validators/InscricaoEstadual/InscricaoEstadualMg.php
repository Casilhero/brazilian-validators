<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualMg
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 13) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        // Primeiro DV: inserir '0' na posição 3 → string de 14 chars
        $ie2 = substr($ie, 0, 3) . '0' . substr($ie, 3);

        $b = 1;
        $somaStr = '';

        for ($i = 0; $i <= 11; $i++) {
            $somaStr .= (string) ((int) $ie2[$i] * $b);
            $b++;

            if ($b === 3) {
                $b = 1;
            }
        }

        $s = 0;

        for ($i = 0; $i < strlen($somaStr); $i++) {
            $s += (int) $somaStr[$i];
        }

        $num = (int) ceil($s / 10) * 10;
        $dig = $num - $s;

        if ($dig !== (int) $ie[11]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        // Segundo DV
        $b = 3;
        $soma = 0;

        for ($i = 0; $i <= 11; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b--;

            if ($b === 1) {
                $b = 11;
            }
        }

        $i = $soma % 11;
        $dig = $i < 2 ? 0 : 11 - $i;

        if ($dig !== (int) $ie[12]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
