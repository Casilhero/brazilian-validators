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
]);

it('rejects invalid cnpj values', function (string $cnpj, string $code): void {
    $result = Cnpj::validate($cnpj);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe($code);
})->with([
    ['04252011000111', ErrorCode::INVALID_CHECKSUM],
    ['00000000000000', ErrorCode::INVALID_FORMAT],
    ['123', ErrorCode::INVALID_LENGTH],
]);
