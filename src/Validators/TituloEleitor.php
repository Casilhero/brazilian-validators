<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class TituloEleitor implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $titulo = Normalizer::digits($value);
        $len    = strlen($titulo);

        // Títulos regulares: 12 dígitos (sequencial de 8 + 2 UF + 2 DV).
        // Títulos emitidos em SP/MG podem ter sequencial de 9 dígitos → 13 dígitos.
        // Fonte: https://ghiorzi.org/DVnew.htm
        if ($len < 12 || $len > 13) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        // Comprimento do sequencial: 8 (padrão) ou 9 (SP/MG)
        $seqLen = $len - 4;

        $uf = (int) substr($titulo, $seqLen, 2);

        if ($uf < 1 || $uf > 28) {
            return ValidationResult::invalid(ErrorCode::INVALID_REGION);
        }

        // Para SP (01) e MG (02), resto 0 é assumido como 1, não como 0.
        $spMg = $uf === 1 || $uf === 2;

        // Pesos percorridos da direita para a esquerda, ciclo 9→2→9.
        // Para sequencial de 8: pesos esq→dir são 2,3,4,5,6,7,8,9.
        // Para sequencial de 9: pesos esq→dir são 9,2,3,4,5,6,7,8,9.
        // A estratégia direita→esquerda com reinício em 9 cobre ambos os casos.
        $sumA = 0;
        $mult = 9;

        for ($i = $seqLen - 1; $i >= 0; $i--) {
            $sumA += (int) $titulo[$i] * $mult;
            $mult--;
            if ($mult < 2) {
                $mult = 9;
            }
        }

        $dv1 = self::mod11($sumA, $spMg);

        $sumB = (int) $titulo[$seqLen] * 7 + (int) $titulo[$seqLen + 1] * 8 + $dv1 * 9;

        $dv2 = self::mod11($sumB, $spMg);

        if ((int) $titulo[$seqLen + 2] !== $dv1 || (int) $titulo[$seqLen + 3] !== $dv2) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }

    /**
     * Calcula o dígito DV módulo 11.
     *
     * Regras gerais: resto 10 → 0.
     * Exceção para SP (UF=01) e MG (UF=02): resto 0 → 1.
     *
     * Fonte: https://ghiorzi.org/DVnew.htm
     */
    private static function mod11(int $sum, bool $spMg = false): int
    {
        $mod = $sum % 11;

        if ($mod === 10) {
            return 0;
        }

        if ($mod === 0) {
            return $spMg ? 1 : 0;
        }

        return $mod;
    }

    public static function generate(): string
    {
        $seqDigits = [];
        for ($i = 0; $i < 8; $i++) {
            $seqDigits[] = random_int(0, 9);
        }

        $uf    = random_int(1, 28);
        $ufStr = str_pad((string) $uf, 2, '0', STR_PAD_LEFT);
        $spMg  = $uf === 1 || $uf === 2;

        $sumA = 0;
        $mult = 9;
        for ($i = 7; $i >= 0; $i--) {
            $sumA += $seqDigits[$i] * $mult;
            $mult--;
            if ($mult < 2) {
                $mult = 9;
            }
        }
        $dv1 = self::mod11($sumA, $spMg);

        $sumB = (int) $ufStr[0] * 7 + (int) $ufStr[1] * 8 + $dv1 * 9;
        $dv2  = self::mod11($sumB, $spMg);

        return implode('', $seqDigits) . $ufStr . $dv1 . $dv2;
    }

    public static function mask(string $value): string
    {
        $d   = Normalizer::digits($value);
        $len = strlen($d);

        if ($len === 13) {
            return substr($d, 0, 5) . ' ' . substr($d, 5, 4) . ' ' . substr($d, 9, 4);
        }

        return substr($d, 0, 4) . ' ' . substr($d, 4, 4) . ' ' . substr($d, 8, 4);
    }
}
