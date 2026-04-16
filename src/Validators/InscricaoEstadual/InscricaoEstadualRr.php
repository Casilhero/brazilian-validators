<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class InscricaoEstadualRr
{
    public static function validate(string $ie): ValidationResult
    {
        if (strlen($ie) !== 9) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (substr($ie, 0, 2) !== '24') {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $b = 1;
        $soma = 0;

        for ($i = 0; $i <= 7; $i++) {
            $soma += (int) $ie[$i] * $b;
            $b++;
        }

        $dig = $soma % 9;

        if ($dig !== (int) $ie[8]) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }
}
