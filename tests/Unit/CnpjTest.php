<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Cnpj;

it('accepts valid cnpj values', function (string $cnpj): void {
    expect(Cnpj::isValid($cnpj))->toBeTrue();
    expect(Cnpj::validate($cnpj)->isValid())->toBeTrue();
})->with([
    '04252011000110',
    '04.252.011/0001-10',
    // Alfanumérico (novo formato SERPRO, vigência jul/2026)
    '12ABC34501DE35',
    '12.ABC.345/01DE-35',
    '12abc34501de35',
    // Valores reais gerados pela Receita Federal (matrizes)
    '47.0WX.WEH/0001-94',
    'PK.9PG.K1Y/0001-85',
    'M2.V2H.JYH/0001-50',
    '7V.8K7.HVY/0001-08',
    'V2.T46.4GP/0001-48',
    'E9.CLH.10B/0001-60',
    'MP.4RJ.RKA/0001-31',
    'JX.58D.B01/0001-71',
    '5M.P2A.A2K/0001-78',
    'SS.KL3.8SD/0001-35',
    // Filiais alfanuméricas
    'MP.4RJ.RKA/ELM5-09',
    'MP.4RJ.RKA/NS92-33',
    'MP.4RJ.RKA/6GN0-20',
]);

it('rejects invalid cnpj values', function (string $cnpj, string $code): void {
    $result = Cnpj::validate($cnpj);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe($code);
})->with([
    ['04252011000111', ErrorCode::INVALID_CHECKSUM],
    ['00000000000000', ErrorCode::INVALID_FORMAT],
    ['123', ErrorCode::INVALID_LENGTH],
    // Alfanumérico inválido
    ['12ABC34501DE36', ErrorCode::INVALID_CHECKSUM],
    ['12ABC34501DEAB', ErrorCode::INVALID_FORMAT],
]);

it('generates a valid cnpj', function (): void {
    expect(Cnpj::isValid(Cnpj::generate()))->toBeTrue();
});

it('masks a cnpj correctly', function (string $input, string $expected): void {
    expect(Cnpj::mask($input))->toBe($expected);
})->with([
    ['04252011000110', '04.252.011/0001-10'],
    ['04.252.011/0001-10', '04.252.011/0001-10'],
]);

it('generates a masked cnpj in correct format', function (): void {
    expect(Cnpj::mask(Cnpj::generate()))->toMatch('/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/');
});
