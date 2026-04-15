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
