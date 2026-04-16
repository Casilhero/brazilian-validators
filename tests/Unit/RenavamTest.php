<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Renavam;

function makeRenavam(string $base): string
{
    $weights = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    $sum = 0;

    for ($i = 0; $i < 10; $i++) {
        $sum += (int) $base[$i] * $weights[$i];
    }

    $digit = ($sum * 10) % 11;

    if ($digit === 10) {
        $digit = 0;
    }

    return $base . (string) $digit;
}

it('accepts valid renavam values', function (): void {
    $valid = makeRenavam('1234567890');

    expect(Renavam::isValid($valid))->toBeTrue();
});

it('accepts formatted renavam values', function (): void {
    $valid = makeRenavam('1234567890');
    $formatted = substr($valid, 0, 4) . '-' . substr($valid, 4);

    expect(Renavam::isValid($formatted))->toBeTrue();
});

it('rejects invalid renavam values', function (): void {
    $valid = makeRenavam('1234567890');
    $invalidChecksum = substr($valid, 0, 10) . ((string) (((int) $valid[10] + 1) % 10));

    expect(Renavam::validate($invalidChecksum)->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect(Renavam::validate('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
});

it('generates a valid renavam', function (): void {
    expect(Renavam::isValid(Renavam::generate()))->toBeTrue();
});

it('masks a renavam correctly', function (): void {
    expect(Renavam::mask('12345678900'))->toBe('12345678900');
    expect(Renavam::mask('1234-5678900'))->toBe('12345678900');
});

it('generates a masked renavam in correct format', function (): void {
    expect(Renavam::mask(Renavam::generate()))->toMatch('/^\d{11}$/');
});
