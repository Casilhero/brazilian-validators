<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Phone;

it('accepts valid phone values', function (string $phone): void {
    expect(Phone::isValid($phone))->toBeTrue();
})->with([
    '1134567890',
    '(11) 98765-4321',
    '5532345678',
]);

it('rejects invalid phone values', function (string $phone, string $code): void {
    expect(Phone::validate($phone)->code())->toBe($code);
})->with([
    ['5511987654321', ErrorCode::INVALID_PREFIX],
    ['00987654321', ErrorCode::INVALID_REGION],
    ['117654321', ErrorCode::INVALID_LENGTH],
    ['11876543210', ErrorCode::INVALID_FORMAT],
]);

it('generates a valid phone', function (): void {
    expect(Phone::isValid(Phone::generate()))->toBeTrue();
});

it('masks a phone correctly', function (string $input, string $expected): void {
    expect(Phone::mask($input))->toBe($expected);
})->with([
    ['1134567890', '(11) 3456-7890'],
    ['11987654321', '(11) 98765-4321'],
]);

it('generates a masked phone in correct format', function (): void {
    expect(Phone::mask(Phone::generate()))->toMatch('/^\(\d{2}\) \d{5}-\d{4}$/');
});
