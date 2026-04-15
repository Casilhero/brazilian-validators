<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\NisPis;

function makeNisPis(string $base): string
{
    $weights = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    $sum = 0;

    for ($i = 0; $i < 10; $i++) {
        $sum += (int) $base[$i] * $weights[$i];
    }

    $remainder = 11 - ($sum % 11);
    $digit = $remainder >= 10 ? 0 : $remainder;

    return $base . (string) $digit;
}

it('accepts valid nis pis values', function (): void {
    $valid = makeNisPis('1234567890');

    expect(NisPis::isValid($valid))->toBeTrue();
    expect(NisPis::isValid(substr($valid, 0, 3) . '.' . substr($valid, 3, 5) . '.' . substr($valid, 8, 2) . '-' . $valid[10]))->toBeTrue();
});

it('rejects invalid nis pis values', function (): void {
    $valid = makeNisPis('1234567890');
    $invalidChecksum = substr($valid, 0, 10) . ((string) (((int) $valid[10] + 1) % 10));

    expect(NisPis::validate($invalidChecksum)->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect(NisPis::validate('00000000000')->code())->toBe(ErrorCode::INVALID_FORMAT);
    expect(NisPis::validate('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
});
