<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Support;

final class BoletoInfo
{
    public function __construct(
        /**
         * Tipo de boleto: 'bancario' (47 dígitos) ou 'arrecadacao' (48 dígitos).
         *
         * @var 'bancario'|'arrecadacao'
         */
        public readonly string $type,
        /** Código do banco (bancário) ou código de identificação do produto/segmento (arrecadação). */
        public readonly string $bankCode,
        /**
         * Código de moeda: '9' para BRL (bancário).
         * Para arrecadação: identificador de valor real — '6' = BRL, '9' = isento.
         */
        public readonly string $currency,
        /**
         * Campo livre do boleto:
         * - Bancário: 25 dígitos do campo livre específico do banco.
         * - Arrecadação: código de barras reconstruído (44 dígitos).
         */
        public readonly string $freeField,
        /** Data de vencimento (bancário). Null para arrecadação ou quando o fator é zero. */
        public readonly ?\DateTimeImmutable $expirationDate,
        /** Valor em centavos (0 quando não especificado ou indeterminável). */
        public readonly int $amount,
    ) {}

    /** Retorna o valor em reais. */
    public function amountInReals(): float
    {
        return $this->amount / 100;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type'            => $this->type,
            'bank_code'       => $this->bankCode,
            'currency'        => $this->currency,
            'free_field'      => $this->freeField,
            'expiration_date' => $this->expirationDate?->format('Y-m-d'),
            'amount'          => $this->amount,
        ];
    }
}
