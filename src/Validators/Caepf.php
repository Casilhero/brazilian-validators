<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

/**
 * Validação de CAEPF (Cadastro de Atividade Econômica da Pessoa Física).
 *
 * Formato: XXX.XXX.XXX/XXX-XX (14 dígitos no total).
 *   - Posições 1-9 : número do CPF do titular (sem o DV)
 *   - Posições 10-12: número de ordem da conta (001-999)
 *   - Posições 13-14: dígitos verificadores (DV)
 *
 * Algoritmo DV módulo 11 (igual ao CNPJ):
 *   Os pesos para os 12 primeiros dígitos são 6,7,8,9,2,3,4,5,6,7,8,9
 *   (equivalente ao ciclo do CNPJ com início em 6).
 *   Para os 13 primeiros dígitos, os pesos são 5,6,7,8,9,2,3,4,5,6,7,8,9.
 *   Resto 10 é considerado 0 (ao contrário do CNPJ, onde resto < 2 → 0).
 *   Ao final, o DV de dois dígitos recebe um ajuste: DV = (DV + 12) % 100.
 *
 * Fonte: https://ghiorzi.org/DVnew.htm
 */
final class Caepf implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $digits = Normalizer::digits($value);

        if (strlen($digits) !== 14) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (Normalizer::isRepeatedDigits($digits)) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $base = substr($digits, 0, 12);
        $dvInformado = (int) substr($digits, 12, 2);

        $dv1 = self::mod11($base);
        $dv2 = self::mod11($base . $dv1);

        $dvCalculado = ($dv1 * 10 + $dv2 + 12) % 100;

        if ($dvInformado !== $dvCalculado) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        $base9 = '';
        for ($i = 0; $i < 9; $i++) {
            $base9 .= (string) random_int(0, 9);
        }

        $base12 = $base9 . '001';

        $dv1 = self::mod11($base12);
        $dv2 = self::mod11($base12 . $dv1);

        $dvCalculado = ($dv1 * 10 + $dv2 + 12) % 100;

        return $base12 . str_pad((string) $dvCalculado, 2, '0', STR_PAD_LEFT);
    }

    public static function mask(string $value): string
    {
        $d = Normalizer::digits($value);

        return substr($d, 0, 3) . '.' . substr($d, 3, 3) . '.' . substr($d, 6, 3) . '/' . substr($d, 9, 3) . '-' . substr($d, 12, 2);
    }

    /**
     * Calcula o dígito verificador módulo 11 para o CAEPF.
     *
     * Os pesos percorrem a base da esquerda para a direita, ciclando 2→9:
     *   - base de 12 dígitos: pesos 6,7,8,9,2,3,4,5,6,7,8,9  (peso inicial = 6)
     *   - base de 13 dígitos: pesos 5,6,7,8,9,2,3,4,5,6,7,8,9 (peso inicial = 5)
     *
     * Fórmula para o peso inicial: 18 - strlen($base).
     * Resto 10 → 0 (diferente do CNPJ onde resto < 2 → 0).
     */
    private static function mod11(string $base): int
    {
        $length = strlen($base);
        $sum    = 0;
        $weight = 18 - $length; // 12 dígitos → 6; 13 dígitos → 5

        for ($i = 0; $i < $length; $i++) {
            $sum += (int) $base[$i] * $weight;
            $weight++;
            if ($weight > 9) {
                $weight = 2;
            }
        }

        $resto = $sum % 11;

        return $resto === 10 ? 0 : $resto;
    }
}
