<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\PhoneDdi;

it('accepts valid phone ddi values', function (string $phone): void {
    expect(PhoneDdi::isValid($phone))->toBeTrue();
})->with([
    '+55 (11) 98765-4321',
    '551134567890',
]);

it('rejects invalid phone ddi values', function (string $phone, string $code): void {
    expect(PhoneDdi::validate($phone)->code())->toBe($code);
})->with([
    ['1111987654321', ErrorCode::INVALID_PREFIX],
    ['5500987654321', ErrorCode::INVALID_REGION],
    ['5511876543210', ErrorCode::INVALID_FORMAT],
]);

it('generates a valid phone ddi', function (): void {
    expect(PhoneDdi::isValid(PhoneDdi::generate()))->toBeTrue();
});

it('masks a phone ddi correctly', function (string $input, string $expected): void {
    expect(PhoneDdi::mask($input))->toBe($expected);
})->with([
    ['551134567890', '+55 (11) 3456-7890'],
    ['5511987654321', '+55 (11) 98765-4321'],
]);

it('generates a masked phone ddi in correct format', function (): void {
    expect(PhoneDdi::mask(PhoneDdi::generate()))->toMatch('/^\+55 \(\d{2}\) \d{5}-\d{4}$/');
});
