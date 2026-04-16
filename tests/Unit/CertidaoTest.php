<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Certidao;

it('accepts valid certidao values', function (string $value): void {
    expect(Certidao::isValid($value))->toBeTrue();
    expect(Certidao::validate($value)->isValid())->toBeTrue();
})->with([
    // 32 dígitos limpos
    '65944702559015199679468255959016',
    // Formatado com espaços e traço (mesmo número)
    '659447 02 55 9015 1 99679 468 2559590-16',
]);

it('rejects invalid certidao values', function (string $value, string $code): void {
    $result = Certidao::validate($value);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe($code);
})->with([
    // Comprimento errado
    ['123', ErrorCode::INVALID_LENGTH],
    // DV inválido (mesmo número com DV trocado)
    ['65944702552015127861468255959032', ErrorCode::INVALID_CHECKSUM],
    // Número formatado com DV errado
    ['659447 02 55 2015 1 27861 468 2559590-32', ErrorCode::INVALID_CHECKSUM],
]);

it('parses valid certidao into structured data', function (): void {
    $info = Certidao::parse('65944702559015199679468255959016');

    expect($info)->not->toBeNull();
    expect($info->codigoServentia)->toBe('659447');
    expect($info->codigoAcervo)->toBe('02');
    expect($info->codigoServico)->toBe('55');
    expect($info->ano)->toBe(9015);
    expect($info->tipoLivro)->toBe(1);
    expect($info->numeroLivro)->toBe('99679');
    expect($info->folha)->toBe('468');
    expect($info->numeroTermo)->toBe('2559590');
});

it('returns null when parsing invalid certidao', function (): void {
    expect(Certidao::parse('123'))->toBeNull();
    expect(Certidao::parse('65944702552015127861468255959032'))->toBeNull();
});

it('generates a valid certidao', function (): void {
    expect(Certidao::isValid(Certidao::generate()))->toBeTrue();
});

it('masks a certidao correctly', function (): void {
    expect(Certidao::mask('65944702559015199679468255959016'))
        ->toBe('659447 02 55 9015 1 99679 468 2559590-16');
    expect(Certidao::mask('659447 02 55 9015 1 99679 468 2559590-16'))
        ->toBe('659447 02 55 9015 1 99679 468 2559590-16');
});

it('generates a masked certidao in correct format', function (): void {
    expect(Certidao::mask(Certidao::generate()))
        ->toMatch('/^\d{6} \d{2} \d{2} \d{4} \d \d{5} \d{3} \d{7}-\d{2}$/');
});

it('returns correct descricao de acervo', function (): void {
    $info = Certidao::parse('65944702559015199679468255959016');

    expect($info)->not->toBeNull();
    expect($info->descricaoAcervo())->toBe('Acervo incorporado (até 31/12/2009)');
});

it('returns correct descricao do livro', function (): void {
    $info = Certidao::parse('65944702559015199679468255959016');

    expect($info)->not->toBeNull();
    expect($info->descricaoLivro())->toBe('Livro A (Nascimento)');
});

it('returns correct toArray from certidao parse', function (): void {
    $info = Certidao::parse('65944702559015199679468255959016');

    expect($info)->not->toBeNull();

    $array = $info->toArray();

    expect($array)->toBe([
        'codigo_serventia' => '659447',
        'codigo_acervo'    => '02',
        'descricao_acervo' => 'Acervo incorporado (até 31/12/2009)',
        'codigo_servico'   => '55',
        'ano'              => 9015,
        'tipo_livro'       => 1,
        'descricao_livro'  => 'Livro A (Nascimento)',
        'numero_livro'     => '99679',
        'folha'            => '468',
        'numero_termo'     => '2559590',
    ]);
});
