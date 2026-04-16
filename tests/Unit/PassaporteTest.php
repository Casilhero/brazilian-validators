<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Passaporte;

it('accepts valid passaporte values', function (string $value): void {
    expect(Passaporte::isValid($value))->toBeTrue();
    expect(Passaporte::validate($value)->isValid())->toBeTrue();
})->with([
    'GB123456',
    'ab123456', // letras minúsculas
    'ZZ999999',
    'AA000000',
]);

it('rejects invalid passaporte values', function (string $value): void {
    $result = Passaporte::validate($value);

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_FORMAT);
})->with([
    'A3948',       // muito curto
    '123456AB',    // dígitos antes das letras
    'AB1234567',   // um dígito a mais
    'AB12345',     // um dígito a menos
    'A1234567',    // apenas uma letra
    'ABC12345',    // três letras
    'AB 123456',   // com espaço
]);

it('generates a valid passaporte', function (): void {
    expect(Passaporte::isValid(Passaporte::generate()))->toBeTrue();
});

it('masks a passaporte correctly', function (): void {
    expect(Passaporte::mask('gb123456'))->toBe('GB123456');
    expect(Passaporte::mask('AB 123456'))->toBe('AB123456');
});

it('generates a masked passaporte in correct format', function (): void {
    expect(Passaporte::mask(Passaporte::generate()))->toMatch('/^[A-Z]{2}\d{6}$/');
});
