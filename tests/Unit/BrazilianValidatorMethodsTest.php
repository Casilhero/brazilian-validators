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

function makeRenavamForFacade(string $base): string
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

function makeTituloEleitorForFacade(string $base): string
{
    $sumA = 0;

    for ($i = 0; $i < 8; $i++) {
        $sumA += (int) $base[$i] * ($i + 2);
    }

    $mod = $sumA % 11;
    $dv1 = ($mod === 10 || $mod === 11) ? 0 : $mod;

    $sumB = (int) $base[8] * 7 + (int) $base[9] * 8 + $dv1 * 9;

    $mod = $sumB % 11;
    $dv2 = ($mod === 10 || $mod === 11) ? 0 : $mod;

    return $base . (string) $dv1 . (string) $dv2;
}

function makeChassiForFacade(string $base16): string
{
    $charValues = [
        'A' => 1, 'J' => 1,
        'B' => 2, 'K' => 2, 'S' => 2,
        'C' => 3, 'L' => 3, 'T' => 3,
        'D' => 4, 'M' => 4, 'U' => 4,
        'E' => 5, 'N' => 5, 'V' => 5,
        'F' => 6, 'W' => 6,
        'G' => 7, 'P' => 7, 'X' => 7,
        'H' => 8, 'Y' => 8,
        'R' => 9, 'Z' => 9,
    ];

    $weights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];
    $vin = strtoupper(substr($base16, 0, 8) . '0' . substr($base16, 8, 8));
    $sum = 0;

    for ($i = 0; $i < 17; $i++) {
        $char = $vin[$i];
        $charValue = is_numeric($char) ? (int) $char : ($charValues[$char] ?? 0);
        $sum += $charValue * $weights[$i];
    }

    $mod = $sum % 11;
    $checkDigit = $mod === 10 ? 'X' : (string) $mod;

    return substr($vin, 0, 8) . $checkDigit . substr($vin, 9, 8);
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
    $validRenavam = makeRenavamForFacade('1234567890');
    $validTituloEleitor = makeTituloEleitorForFacade('1234567801');
    $validChassi = makeChassiForFacade('1HGCM8263A004352');

    expect(BrazilianValidator::cpf('52998224725'))->toBeTrue();
    expect(BrazilianValidator::cnpj('04252011000110'))->toBeTrue();
    expect(BrazilianValidator::cpfCnpj('04.252.011/0001-10'))->toBeTrue();
    expect(BrazilianValidator::suframa($validSuframa))->toBeTrue();
    expect(BrazilianValidator::nisPis($validNisPis))->toBeTrue();
    expect(BrazilianValidator::phone('(11) 98765-4321'))->toBeTrue();
    expect(BrazilianValidator::phoneDdi('+55 (11) 98765-4321'))->toBeTrue();
    expect(BrazilianValidator::cnh($validCnh))->toBeTrue();
    expect(BrazilianValidator::cns($validCns))->toBeTrue();
    expect(BrazilianValidator::renavam($validRenavam))->toBeTrue();
    expect(BrazilianValidator::tituloEleitor($validTituloEleitor))->toBeTrue();
    expect(BrazilianValidator::chassi($validChassi))->toBeTrue();
    expect(BrazilianValidator::inscricaoEstadual('110042490114', 'SP'))->toBeTrue();
    expect(BrazilianValidator::processoJudicial('00000014120248010001'))->toBeTrue();

    expect(BrazilianValidator::cpfResult('11111111111')->code())->toBe(ErrorCode::INVALID_FORMAT);
    expect(BrazilianValidator::cnpjResult('00000000000000')->code())->toBe(ErrorCode::INVALID_FORMAT);
    expect(BrazilianValidator::cpfCnpjResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::suframaResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::nisPisResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::phoneResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::phoneDdiResult('123')->code())->toBe(ErrorCode::INVALID_PREFIX);
    expect(BrazilianValidator::cnhResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::cnsResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::renavamResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::tituloEleitorResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::chassiResult('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(BrazilianValidator::inscricaoEstadualResult('123', 'XX')->code())->toBe(ErrorCode::INVALID_REGION);
    expect(BrazilianValidator::processoJudicialResult('1234')->code())->toBe(ErrorCode::INVALID_LENGTH);
});

it('covers generate and mask facade methods in core', function (): void {
    expect(BrazilianValidator::cpf(BrazilianValidator::cpfGenerate()))->toBeTrue();
    expect(BrazilianValidator::cpfMask('52998224725'))->toBe('529.982.247-25');

    expect(BrazilianValidator::cnpj(BrazilianValidator::cnpjGenerate()))->toBeTrue();
    expect(BrazilianValidator::cnpjMask('04252011000110'))->toBe('04.252.011/0001-10');

    expect(BrazilianValidator::cpfCnpj(BrazilianValidator::cpfCnpjGenerate()))->toBeTrue();

    expect(BrazilianValidator::cnh(BrazilianValidator::cnhGenerate()))->toBeTrue();
    expect(BrazilianValidator::cns(BrazilianValidator::cnsGenerate()))->toBeTrue();
    expect(BrazilianValidator::nisPis(BrazilianValidator::nisPisGenerate()))->toBeTrue();
    expect(BrazilianValidator::phone(BrazilianValidator::phoneGenerate()))->toBeTrue();
    expect(BrazilianValidator::phoneDdi(BrazilianValidator::phoneDdiGenerate()))->toBeTrue();
    expect(BrazilianValidator::renavam(BrazilianValidator::renavamGenerate()))->toBeTrue();
    expect(BrazilianValidator::suframa(BrazilianValidator::suframaGenerate()))->toBeTrue();
    expect(BrazilianValidator::certidao(BrazilianValidator::certidaoGenerate()))->toBeTrue();
    expect(BrazilianValidator::passaporte(BrazilianValidator::passaporteGenerate()))->toBeTrue();
    expect(BrazilianValidator::caepf(BrazilianValidator::caepfGenerate()))->toBeTrue();
    expect(BrazilianValidator::chassi(BrazilianValidator::chassiGenerate()))->toBeTrue();
    expect(BrazilianValidator::tituloEleitor(BrazilianValidator::tituloEleitorGenerate()))->toBeTrue();
    expect(BrazilianValidator::processoJudicial(BrazilianValidator::processoJudicialGenerate()))->toBeTrue();

    expect(BrazilianValidator::caepfMask('29311861000184'))->toBe('293.118.610/001-84');
    expect(BrazilianValidator::certidaoMask('65944702559015199679468255959016'))
        ->toBe('659447 02 55 9015 1 99679 468 2559590-16');
    expect(BrazilianValidator::passaporteMask('gb123456'))->toBe('GB123456');
    expect(BrazilianValidator::chassiMask('1HGCM82633A004352'))->toBe('1HGCM82633A004352');
    expect(BrazilianValidator::tituloEleitorMask('123456780191'))->toBe('1234 5678 0191');
    expect(BrazilianValidator::processoJudicialMask('00000014120248010001'))->toBe('0000001-41.2024.8.01.0001');
});
