<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Cpf;

it('accepts valid cpf values', function (string $cpf): void {
    expect(Cpf::isValid($cpf))->toBeTrue();
    expect(Cpf::validate($cpf)->isValid())->toBeTrue();
})->with([
    '52998224725',
    '529.982.247-25',
]);

it('rejects invalid cpf values', function (string $cpf, string $code): void {
    $result = Cpf::validate($cpf);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe($code);
})->with([
    ['52998224724', ErrorCode::INVALID_CHECKSUM],
    ['11111111111', ErrorCode::INVALID_FORMAT],
    ['123', ErrorCode::INVALID_LENGTH],
]);

it('generates a valid cpf', function (): void {
    expect(Cpf::isValid(Cpf::generate()))->toBeTrue();
});

it('masks a cpf correctly', function (string $input, string $expected): void {
    expect(Cpf::mask($input))->toBe($expected);
})->with([
    ['52998224725', '529.982.247-25'],
    ['529.982.247-25', '529.982.247-25'],
]);

it('generates a masked cpf in correct format', function (): void {
    expect(Cpf::mask(Cpf::generate()))->toMatch('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/');
});
