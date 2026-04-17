<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\BoletoInfo;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

/**
 * Validator para Boleto Bancário e Boleto de Arrecadação brasileiros.
 *
 * Boleto Bancário (47 dígitos):
 *   Linha digitável no formato: AAAAA.AAAAA AAAAA.AAAAAA AAAAA.AAAAAA A AAAAAAAAAAAAAA
 *   Valida os DVs dos 3 campos usando Módulo 10.
 *
 * Boleto de Arrecadação (48 dígitos):
 *   Formato: AAAAAAAAAAA-A AAAAAAAAAAA-A AAAAAAAAAAA-A AAAAAAAAAAA-A
 *   Valida os DVs dos 4 blocos usando Módulo 10.
 */
final class Boleto
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $digits = Normalizer::digits($value);
        $length = strlen($digits);

        if ($length === 47) {
            return self::validateBancario($digits)
                ? ValidationResult::valid()
                : ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        if ($length === 48) {
            return self::validateArrecadacao($digits)
                ? ValidationResult::valid()
                : ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
    }

    /**
     * Analisa o boleto e retorna suas informações estruturadas.
     * Requer que o boleto seja válido; retorna null caso contrário.
     */
    public static function parse(string $value): ?BoletoInfo
    {
        if (! self::isValid($value)) {
            return null;
        }

        $digits = Normalizer::digits($value);

        if (strlen($digits) === 47) {
            $bankCode  = substr($digits, 0, 3);
            $currency  = $digits[3];
            // campo livre: free1 (pos 4-8) + free2 (10-19) + free3 (21-30) = 25 dígitos
            $freeField = substr($digits, 4, 5) . substr($digits, 10, 10) . substr($digits, 21, 10);

            $factor         = (int) substr($digits, 33, 4);
            $expirationDate = null;

            if ($factor > 0) {
                $base           = new \DateTimeImmutable('1997-10-07');
                $expirationDate = $base->modify("+{$factor} days");
            }

            $amount = (int) substr($digits, 37, 10);

            return new BoletoInfo(
                type:           'bancario',
                bankCode:       $bankCode,
                currency:       $currency,
                freeField:      $freeField,
                expirationDate: $expirationDate,
                amount:         $amount,
            );
        }

        // Arrecadação: reconstrói código de barras removendo os 4 DVs de bloco (pos 11, 23, 35, 47)
        $barcode =
            substr($digits, 0, 11) .
            substr($digits, 12, 11) .
            substr($digits, 24, 11) .
            substr($digits, 36, 11);

        $bankCode  = substr($barcode, 0, 3);
        // barcode[2] = identificador de valor real: '6' = BRL, '7' = quantidade, '9' = isento
        $currency  = $barcode[2];
        // Valor: barcode[3..13] (11 dígitos) quando currency === '6'
        $amount    = $currency === '6' ? (int) substr($barcode, 3, 11) : 0;
        // freeField = código de barras completo (44 dígitos) para decodificação avançada
        $freeField = $barcode;

        return new BoletoInfo(
            type:           'arrecadacao',
            bankCode:       $bankCode,
            currency:       $currency,
            freeField:      $freeField,
            expirationDate: null,
            amount:         $amount,
        );
    }

    /**
     * Gera uma linha digitável de boleto bancário válida (47 dígitos).
     * Contém DVs de campo (mod10) e DV do código de barras (mod11) corretos.
     */
    public static function generate(): string
    {
        $bank = str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
        $currency = '9';

        $free = '';
        for ($i = 0; $i < 25; $i++) {
            $free .= (string) random_int(0, 9);
        }

        $free1 = substr($free, 0, 5);
        $free2 = substr($free, 5, 10);
        $free3 = substr($free, 15, 10);

        $dv1 = self::mod10($bank.$currency.$free1);
        $dv2 = self::mod10($free2);
        $dv3 = self::mod10($free3);

        // Fator de vencimento: dias desde 07/10/1997
        $baseDate = mktime(0, 0, 0, 10, 7, 1997);
        $days = (int) floor((time() - $baseDate) / 86400);
        $factor = str_pad((string) ($days % 9999), 4, '0', STR_PAD_LEFT);
        $amount = '0000000000';

        // DV geral do código de barras (mod11)
        $barcodeWithoutDv = $bank.$currency.$factor.$amount.$free1.$free2.$free3;
        $dvGeral = self::mod11Barcode($barcodeWithoutDv);

        return $bank.$currency.$free1.$dv1.$free2.$dv2.$free3.$dv3.$dvGeral.$factor.$amount;
    }

    /**
     * Aplica a máscara ao boleto.
     * - 47 dígitos (bancário): `AAAAA.AAAAA AAAAA.AAAAAA AAAAA.AAAAAA A AAAAAAAAAAAAAA`
     * - 48 dígitos (arrecadação): `AAAAAAAAAAA-A AAAAAAAAAAA-A AAAAAAAAAAA-A AAAAAAAAAAA-A`
     * - Valores parciais: máscara progressiva no padrão bancário.
     */
    public static function mask(string $value): string
    {
        $digits = Normalizer::digits($value);

        if (strlen($digits) === 48) {
            $b = static fn (string $d, int $start): string =>
                substr($d, $start, 11).'-'.$d[$start + 11];

            return $b($digits, 0).' '.$b($digits, 12).' '.$b($digits, 24).' '.$b($digits, 36);
        }

        // Bancário (progressivo): 00000.00000 00000.000000 00000.000000 0 00000000000000
        $d = substr($digits, 0, 47);
        $pattern = '00000.00000 00000.000000 00000.000000 0 00000000000000';
        $result = '';
        $di = 0;
        $len = strlen($d);

        for ($i = 0; $i < strlen($pattern); $i++) {
            $ch = $pattern[$i];
            if ($ch === '0') {
                if ($di >= $len) {
                    break;
                }
                $result .= $d[$di++];
            } elseif ($di < $len) {
                $result .= $ch;
            }
        }

        return $result;
    }

    /**
     * Módulo 10 para DVs de campo do boleto.
     * Percorre da direita para a esquerda, multiplicando alternadamente por 2 e 1
     * (dígito mais à direita × 2). Se produto > 9, subtrai 9.
     */
    private static function mod10(string $value): int
    {
        $sum = 0;
        $multiplier = 2;

        for ($i = strlen($value) - 1; $i >= 0; $i--) {
            $result = (int) $value[$i] * $multiplier;
            if ($result > 9) {
                $result -= 9;
            }
            $sum += $result;
            $multiplier = $multiplier === 2 ? 1 : 2;
        }

        return (10 - ($sum % 10)) % 10;
    }

    /**
     * Módulo 11 para o DV do código de barras do boleto bancário.
     * Pesos 2,3,4,5,6,7 ciclando da direita para a esquerda.
     * Resto 0 ou 1 → DV = 1; demais → DV = 11 - resto.
     */
    private static function mod11Barcode(string $value): int
    {
        $sum = 0;
        $weight = 2;

        for ($i = strlen($value) - 1; $i >= 0; $i--) {
            $sum += (int) $value[$i] * $weight;
            $weight = $weight < 7 ? $weight + 1 : 2;
        }

        $remainder = $sum % 11;

        return ($remainder === 0 || $remainder === 1) ? 1 : 11 - $remainder;
    }

    /** Valida os 3 DVs de campo (mod10) do boleto bancário de 47 dígitos. */
    private static function validateBancario(string $digits): bool
    {
        return self::mod10(substr($digits, 0, 9)) === (int) $digits[9]
            && self::mod10(substr($digits, 10, 10)) === (int) $digits[20]
            && self::mod10(substr($digits, 21, 10)) === (int) $digits[31];
    }

    /** Valida os 4 DVs de bloco (mod10) do boleto de arrecadação de 48 dígitos. */
    private static function validateArrecadacao(string $digits): bool
    {
        for ($b = 0; $b < 4; $b++) {
            $start = $b * 12;
            if (self::mod10(substr($digits, $start, 11)) !== (int) $digits[$start + 11]) {
                return false;
            }
        }

        return true;
    }
}
