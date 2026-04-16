<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Caepf;

it('accepts valid caepf values', function (string $value): void {
    expect(Caepf::isValid($value))->toBeTrue();
    expect(Caepf::validate($value)->isValid())->toBeTrue();
})->with([
    // Exemplo canônico da fonte https://ghiorzi.org/DVnew.htm
    // base 293118610001 → DV1=7, DV2=2 → DV ajustado = (72+12)%100 = 84
    '29311861000184',
    // Formatado com máscara XXX.XXX.XXX/XXX-XX
    '293.118.610/001-84',
]);

it('rejects caepf with wrong length', function (string $value): void {
    $result = Caepf::validate($value);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_LENGTH);
})->with([
    ['123'],
    ['2931186100018'],
    ['293118610001840'],
]);

it('rejects caepf with all repeated digits', function (): void {
    $result = Caepf::validate('11111111111111');

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_FORMAT);
});

it('rejects caepf with invalid checksum', function (string $value): void {
    $result = Caepf::validate($value);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_CHECKSUM);
})->with([
    ['29311861000185'],
    ['29311861000100'],
    ['293.118.610/001-00'],
]);

it('generates a valid caepf', function (): void {
    expect(Caepf::isValid(Caepf::generate()))->toBeTrue();
});

it('masks a caepf correctly', function (string $input, string $expected): void {
    expect(Caepf::mask($input))->toBe($expected);
})->with([
    ['29311861000184', '293.118.610/001-84'],
    ['293.118.610/001-84', '293.118.610/001-84'],
]);

it('generates a masked caepf in correct format', function (): void {
    expect(Caepf::mask(Caepf::generate()))->toMatch('/^\d{3}\.\d{3}\.\d{3}\/\d{3}-\d{2}$/');
});
