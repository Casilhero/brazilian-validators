<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Cns;

function makeCns(string $base): string
{
    for ($digit = 0; $digit <= 9; $digit++) {
        $candidate = $base . (string) $digit;
        $sum = 0;

        for ($i = 0; $i < 15; $i++) {
            $sum += (int) $candidate[$i] * (15 - $i);
        }

        if ($sum % 11 === 0) {
            return $candidate;
        }
    }

    throw new RuntimeException('Unable to generate CNS value.');
}

it('accepts valid cns values', function (): void {
    $valid = makeCns('71234567890123');

    expect(Cns::isValid($valid))->toBeTrue();
});

it('rejects invalid cns values', function (): void {
    $valid = makeCns('71234567890123');
    $invalidChecksum = substr($valid, 0, 14) . ((string) (((int) $valid[14] + 1) % 10));

    expect(Cns::validate($invalidChecksum)->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect(Cns::validate('312345678901234')->code())->toBe(ErrorCode::INVALID_PREFIX);
    expect(Cns::validate('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
});

it('generates a valid cns', function (): void {
    expect(Cns::isValid(Cns::generate()))->toBeTrue();
});

it('masks a cns correctly', function (): void {
    expect(Cns::mask('712345678901236'))->toBe('712 3456 7890 1236');
    expect(Cns::mask('712 3456 7890 1236'))->toBe('712 3456 7890 1236');
});

it('generates a masked cns in correct format', function (): void {
    expect(Cns::mask(Cns::generate()))->toMatch('/^\d{3} \d{4} \d{4} \d{4}$/');
});
