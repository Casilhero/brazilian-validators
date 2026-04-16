<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\Tribunais;
use Casilhero\BrazilianValidators\Support\ValidationResult;

/**
 * Validação de número de processo judicial (CNJ Res. 65/2008).
 *
 * Formato: NNNNNNN-DD.AAAA.J.TR.OOOO (20 dígitos no total).
 *   - NNNNNNN : número sequencial do processo (7 dígitos)
 *   - DD      : dígitos verificadores (2 dígitos)
 *   - AAAA    : ano de ajuizamento (4 dígitos)
 *   - J       : segmento de justiça (1 dígito, 1-9)
 *   - TR      : tribunal / órgão (2 dígitos)
 *   - OOOO    : origem (4 dígitos)
 *
 * Algoritmo DV: Módulo 97 Base 10 (ISO 7064), em três operações iterativas
 * para evitar inteiros grandes:
 *   op1      = N % 97
 *   op2      = (op1 || AAAA || J || TR) % 97
 *   opFinal  = (op2 || OOOO || DD) % 97  → deve ser igual a 1
 */
final class ProcessoJudicial implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $raw = Normalizer::digits($value);

        if (strlen($raw) !== 20) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $n  = substr($raw, 0, 7);
        $d  = substr($raw, 7, 2);
        $a  = substr($raw, 9, 4);
        $j  = substr($raw, 13, 1);
        $tr = substr($raw, 14, 2);
        $o  = substr($raw, 16, 4);

        $op1     = (int) $n % 97;
        $op2     = (int) ((string) $op1 . $a . $j . $tr) % 97;
        $opFinal = (int) ((string) $op2 . $o . $d) % 97;

        if ($opFinal !== 1) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        if (Tribunais::get((int) $j, (int) $tr) === null) {
            return ValidationResult::invalid(ErrorCode::INVALID_REGION);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        $allData = Tribunais::all();
        $jKeys   = array_keys($allData);
        $j       = $jKeys[array_rand($jKeys)];
        $trKeys  = array_keys($allData[$j]);
        $tr      = $trKeys[array_rand($trKeys)];

        $n     = str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT);
        $a     = str_pad((string) random_int(2000, (int) date('Y')), 4, '0', STR_PAD_LEFT);
        $jStr  = (string) $j;
        $trStr = str_pad((string) $tr, 2, '0', STR_PAD_LEFT);
        $o     = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $op1 = (int) $n % 97;
        $op2 = (int) ((string) $op1 . $a . $jStr . $trStr) % 97;
        $rem = ((int) ((string) $op2 . $o) * 100) % 97;
        $d   = str_pad((string) (98 - $rem), 2, '0', STR_PAD_LEFT);

        return $n . $d . $a . $jStr . $trStr . $o;
    }

    public static function mask(string $value): string
    {
        $raw = Normalizer::digits($value);

        if (strlen($raw) !== 20) {
            return $value;
        }

        return substr($raw, 0, 7) . '-'
            . substr($raw, 7, 2) . '.'
            . substr($raw, 9, 4) . '.'
            . substr($raw, 13, 1) . '.'
            . substr($raw, 14, 2) . '.'
            . substr($raw, 16, 4);
    }
}
