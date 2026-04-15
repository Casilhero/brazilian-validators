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
