<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\CertidaoInfo;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Normalizer;
use Casilhero\BrazilianValidators\Support\ValidationResult;

/**
 * Validação de Certidão de Nascimento, Casamento e Óbito.
 *
 * Estrutura da matrícula (32 dígitos):
 *   1-6   Código Nacional da Serventia
 *   7-8   Código do acervo (01=próprio, 02=incorporado pré-2010)
 *   9-10  Código do serviço (fixo: 55)
 *   11-14 Ano do registro
 *   15    Tipo do livro (1-9)
 *   16-20 Número do livro
 *   21-23 Número da folha
 *   24-30 Número do termo
 *   31-32 Dígito verificador
 *
 * Algoritmo: módulo 11 com pesos 2-10 ciclando em 0.
 * Fonte: https://atos.cnj.jus.br/files/provimento/provimento_3_17112009_26102012180506.pdf
 */
final class Certidao implements BrazilianValidatorContract
{
    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $digits = Normalizer::digits($value);

        if (strlen($digits) !== 32) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        $num = substr($digits, 0, 30);
        $dv  = substr($digits, 30, 2);

        $dv1 = self::somaPonderada($num) % 11;
        $dv1 = $dv1 > 9 ? 1 : $dv1;

        $dv2 = self::somaPonderada($num . $dv1) % 11;
        $dv2 = $dv2 > 9 ? 1 : $dv2;

        if ($dv !== $dv1 . $dv2) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }

    /**
     * Analisa a certidão e retorna suas informações estruturadas.
     * Requer que o número seja válido; retorna null caso contrário.
     */
    public static function parse(string $value): ?CertidaoInfo
    {
        if (! self::isValid($value)) {
            return null;
        }

        $digits = Normalizer::digits($value);

        return new CertidaoInfo(
            codigoServentia: substr($digits, 0, 6),
            codigoAcervo:    substr($digits, 6, 2),
            codigoServico:   substr($digits, 8, 2),
            ano:             (int) substr($digits, 10, 4),
            tipoLivro:       (int) $digits[14],
            numeroLivro:     substr($digits, 15, 5),
            folha:           substr($digits, 20, 3),
            numeroTermo:     substr($digits, 23, 7),
        );
    }

    public static function generate(): string
    {
        $serventia = str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        $acervo    = '01';
        $servico   = '55';
        $ano       = (string) random_int(2010, (int) date('Y'));
        $tipo      = '1';
        $livro     = str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        $folha     = str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT);
        $termo     = str_pad((string) random_int(1, 9999999), 7, '0', STR_PAD_LEFT);

        $num = $serventia . $acervo . $servico . $ano . $tipo . $livro . $folha . $termo;

        $dv1 = self::somaPonderada($num) % 11;
        $dv1 = $dv1 > 9 ? 1 : $dv1;

        $dv2 = self::somaPonderada($num . $dv1) % 11;
        $dv2 = $dv2 > 9 ? 1 : $dv2;

        return $num . $dv1 . $dv2;
    }

    public static function mask(string $value): string
    {
        $d = Normalizer::digits($value);

        return sprintf(
            '%s %s %s %s %s %s %s %s-%s',
            substr($d, 0, 6),
            substr($d, 6, 2),
            substr($d, 8, 2),
            substr($d, 10, 4),
            $d[14],
            substr($d, 15, 5),
            substr($d, 20, 3),
            substr($d, 23, 7),
            substr($d, 30, 2)
        );
    }

    private static function somaPonderada(string $value): int
    {
        $soma         = 0;
        $multiplicador = 32 - strlen($value);

        for ($i = 0; $i < strlen($value); $i++) {
            $soma += (int) $value[$i] * $multiplicador;
            $multiplicador++;
            if ($multiplicador > 10) {
                $multiplicador = 0;
            }
        }

        return $soma;
    }
}
