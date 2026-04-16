<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Support;

final class CertidaoInfo
{
    private const TIPO_LIVRO = [
        1 => 'Livro A (Nascimento)',
        2 => 'Livro B (Casamento)',
        3 => 'Livro B Auxiliar (Casamento Religioso com efeito civil)',
        4 => 'Livro C (Óbito)',
        5 => 'Livro C Auxiliar (Natimorto)',
        6 => 'Livro D (Registro de Proclamas)',
        7 => 'Livro E (Demais atos relativos ao registro civil ou livro E único)',
        8 => 'Livro E (Desdobrado para registro específico das Emancipações)',
        9 => 'Livro E (Desdobrado para registro específico das Interdições)',
    ];

    private const CODIGO_ACERVO = [
        '01' => 'Acervo próprio',
        '02' => 'Acervo incorporado (até 31/12/2009)',
    ];

    public function __construct(
        public readonly string $codigoServentia,
        public readonly string $codigoAcervo,
        public readonly string $codigoServico,
        public readonly int $ano,
        public readonly int $tipoLivro,
        public readonly string $numeroLivro,
        public readonly string $folha,
        public readonly string $numeroTermo,
    ) {
    }

    public function descricaoAcervo(): string
    {
        return self::CODIGO_ACERVO[$this->codigoAcervo] ?? 'Desconhecido';
    }

    public function descricaoLivro(): string
    {
        return self::TIPO_LIVRO[$this->tipoLivro] ?? 'Desconhecido';
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'codigo_serventia' => $this->codigoServentia,
            'codigo_acervo'    => $this->codigoAcervo,
            'descricao_acervo' => $this->descricaoAcervo(),
            'codigo_servico'   => $this->codigoServico,
            'ano'              => $this->ano,
            'tipo_livro'       => $this->tipoLivro,
            'descricao_livro'  => $this->descricaoLivro(),
            'numero_livro'     => $this->numeroLivro,
            'folha'            => $this->folha,
            'numero_termo'     => $this->numeroTermo,
        ];
    }
}
