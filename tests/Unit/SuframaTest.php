<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Suframa;

function makeSuframa(string $base): string
{
    $sum = 0;
    $weight = 9;

    for ($i = 0; $i < 8; $i++) {
        $sum += (int) $base[$i] * $weight;
        $weight--;
    }

    $digit = 11 - ($sum % 11);

    if ($digit >= 10) {
        $digit = 0;
    }

    return $base . (string) $digit;
}

it('accepts valid suframa values', function (): void {
    $valid = makeSuframa('12345678');

    expect(Suframa::isValid($valid))->toBeTrue();
});

it('accepts real-world suframa values', function (string $suframa): void {
    expect(Suframa::isValid($suframa))->toBeTrue();
})->with([
    '550309012',
    '100698107',
    '111279100',
    '100955100',
    '101040105',
    '600215105',
    '110344103',
    '100826105',
    '100965105',
]);

it('rejects invalid suframa values', function (): void {
    $valid = makeSuframa('12345678');
    $invalidChecksum = substr($valid, 0, 8) . ((string) (((int) $valid[8] + 1) % 10));

    expect(Suframa::validate($invalidChecksum)->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect(Suframa::validate('001234567')->code())->toBe(ErrorCode::INVALID_PREFIX);
    expect(Suframa::validate('111111111')->code())->toBe(ErrorCode::INVALID_FORMAT);
    expect(Suframa::validate('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
});

it('generates a valid suframa', function (): void {
    expect(Suframa::isValid(Suframa::generate()))->toBeTrue();
});

it('masks a suframa correctly', function (): void {
    expect(Suframa::mask('550309012'))->toBe('550309012');
});

it('generates a masked suframa in correct format', function (): void {
    expect(Suframa::mask(Suframa::generate()))->toMatch('/^\d{9}$/');
});
