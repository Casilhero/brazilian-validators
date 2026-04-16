<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\CpfCnpj;

it('accepts valid cpf or cnpj values', function (string $document): void {
    expect(CpfCnpj::isValid($document))->toBeTrue();
})->with([
    '529.982.247-25',
    '04.252.011/0001-10',
]);

it('rejects invalid cpf or cnpj values', function (string $document, string $code): void {
    $result = CpfCnpj::validate($document);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe($code);
})->with([
    ['11111111111', ErrorCode::INVALID_FORMAT],
    ['04252011000111', ErrorCode::INVALID_CHECKSUM],
    ['12345', ErrorCode::INVALID_LENGTH],
]);

it('generates a valid cpf or cnpj', function (): void {
    expect(CpfCnpj::isValid(CpfCnpj::generate()))->toBeTrue();
});

it('masks a cpf or cnpj correctly', function (string $input, string $expected): void {
    expect(CpfCnpj::mask($input))->toBe($expected);
})->with([
    ['52998224725', '529.982.247-25'],
    ['04252011000110', '04.252.011/0001-10'],
]);

it('generates a masked cpf or cnpj in correct format', function (): void {
    $masked = CpfCnpj::mask(CpfCnpj::generate());
    expect($masked)->toMatch('/^(\d{3}\.\d{3}\.\d{3}-\d{2}|\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2})$/');
});
