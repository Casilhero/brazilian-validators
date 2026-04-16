<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Cnh;

function makeCnh(string $base): string
{
    $sum = 0;
    $weight = 9;

    for ($i = 0; $i < 9; $i++) {
        $sum += (int) $base[$i] * $weight;
        $weight--;
    }

    $first = $sum % 11;
    $discount = 0;

    if ($first >= 10) {
        $first = 0;
        $discount = 2;
    }

    $sum = 0;

    for ($i = 0, $weight = 1; $i < 9; $i++, $weight++) {
        $sum += (int) $base[$i] * $weight;
    }

    $second = ($sum % 11) - $discount;

    if ($second < 0) {
        $second += 11;
    }

    if ($second >= 10) {
        $second = 0;
    }

    return $base . (string) $first . (string) $second;
}

function makeCnhWithDiscountBranch(): string
{
    for ($number = 0; $number < 1_000_000; $number++) {
        $base = str_pad((string) $number, 9, '0', STR_PAD_LEFT);

        $sum = 0;

        for ($i = 0, $weight = 9; $i < 9; $i++, $weight--) {
            $sum += (int) $base[$i] * $weight;
        }

        if ($sum % 11 !== 10) {
            continue;
        }

        $candidate = makeCnh($base);

        if (Cnh::isValid($candidate)) {
            return $candidate;
        }
    }

    throw new RuntimeException('Could not generate CNH with discount branch.');
}

it('accepts valid cnh values', function (): void {
    $valid = makeCnh('123456789');

    expect(Cnh::isValid($valid))->toBeTrue();
});

it('accepts valid cnh values with discount branch', function (): void {
    $valid = makeCnhWithDiscountBranch();

    expect(Cnh::isValid($valid))->toBeTrue();
});

it('rejects invalid cnh values', function (): void {
    $valid = makeCnh('123456789');
    $invalidChecksum = substr($valid, 0, 10) . ((string) (((int) $valid[10] + 1) % 10));

    expect(Cnh::validate($invalidChecksum)->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect(Cnh::validate('11111111111')->code())->toBe(ErrorCode::INVALID_FORMAT);
    expect(Cnh::validate('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
});

it('accepts known valid cnh values', function (): void {
    expect(Cnh::isValid('81952476011'))->toBeTrue();
    expect(Cnh::isValid('33798941353'))->toBeTrue();
    expect(Cnh::isValid('87222700600'))->toBeTrue();
});

it('rejects known invalid cnh checksums', function (): void {
    expect(Cnh::validate('02102234243')->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect(Cnh::validate('13798941353')->code())->toBe(ErrorCode::INVALID_CHECKSUM);
});

it('generates a valid cnh', function (): void {
    expect(Cnh::isValid(Cnh::generate()))->toBeTrue();
});

it('masks a cnh correctly', function (): void {
    expect(Cnh::mask('12345678900'))->toBe('12345678900');
    expect(Cnh::mask('123 456 789 00'))->toBe('12345678900');
});

it('generates a masked cnh in correct format', function (): void {
    expect(Cnh::mask(Cnh::generate()))->toMatch('/^\d{11}$/');
});
