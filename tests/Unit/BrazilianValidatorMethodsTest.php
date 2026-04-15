<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\BrazilianValidator;
use Casilhero\BrazilianValidators\Support\ErrorCode;

function makeNisPisForFacade(string $base): string
{
    $weights = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    $sum = 0;

    for ($i = 0; $i < 10; $i++) {
        $sum += (int) $base[$i] * $weights[$i];
    }

    $remainder = 11 - ($sum % 11);

    return $base . (string) ($remainder >= 10 ? 0 : $remainder);
}

function makeSuframaForFacade(string $base): string
{
    $sum = 0;
    $weight = 9;

    for ($i = 0; $i < 8; $i++) {
        $sum += (int) $base[$i] * $weight;
        $weight--;
    }

    $digit = 11 - ($sum % 11);

    return $base . (string) ($digit >= 10 ? 0 : $digit);
}

function makeCnsForFacade(string $base): string
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

    throw new RuntimeException('Cannot generate valid CNS for tests.');
}

function makeCnhForFacade(string $base): string
{
    $sum = 0;

    for ($i = 0, $weight = 9; $i < 9; $i++, $weight--) {
        $sum += (int) $base[$i] * $weight;
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

it('covers all static facade methods in core', function (): void {
    $validNisPis = makeNisPisForFacade('1234567890');
    $validSuframa = makeSuframaForFacade('12345678');
    $validCnh = makeCnhForFacade('123456789');
    $validCns = makeCnsForFacade('71234567890123');

    expect(BrazilianValidator::cpf('52998224725'))->toBeTrue();
    expect(BrazilianValidator::cnpj('04252011000110'))->toBeTrue();
    expect(BrazilianValidator::cpfCnpj('04.252.011/0001-10'))->toBeTrue();
    expect(BrazilianValidator::suframa($validSuframa))->toBeTrue();
    expect(BrazilianValidator::nisPis($validNisPis))->toBeTrue();
    expect(BrazilianValidator::phone('(11) 98765-4321'))->toBeTrue();
    expect(BrazilianValidator::phoneDdi('+55 (11) 98765-4321'))->toBeTrue();
    expect(BrazilianValidator::cnh($validCnh))->toBeTrue();
    expect(BrazilianValidator::cns($validCns))->toBeTrue();

    expect(BrazilianValidator::cpfResult('11111111111')->code())->toBe(ErrorCode::INVALID_FORMAT);
    expect(BrazilianValidator::cnpjResult('00000000000000')->code())->toBe(ErrorCode::INVALID_FORMAT);
    expect(BrazilianValidator::cpfCnpjResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::suframaResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::nisPisResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::phoneResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::phoneDdiResult('123')->code())->toBe(ErrorCode::INVALID_PREFIX);
    expect(BrazilianValidator::cnhResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::cnsResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
});
