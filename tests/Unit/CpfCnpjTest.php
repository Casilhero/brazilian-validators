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
