<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\ProcessoJudicial;

it('accepts valid processo judicial values', function (string $value): void {
    expect(ProcessoJudicial::isValid($value))->toBeTrue();
})->with([
    '00000014120248010001',           // dígitos puros  – J=8 (Estadual), TR=01 (TJAC)
    '0000001-41.2024.8.01.0001',     // formato mascarado
    '12345676520205050010',           // J=5 (Trabalho), TR=05 (TRT5)
    '00000019620244900001',           // J=4 (Federal),  TR=90 (CJF)
    '00000011520245900001',           // J=5 (Trabalho), TR=90 (CSJT)
]);

it('rejects processo judicial with invalid length', function (): void {
    expect(ProcessoJudicial::validate('1234')->code())->toBe(ErrorCode::INVALID_LENGTH);
});

it('rejects processo judicial with invalid checksum', function (): void {
    // último dígito de OOOO alterado de 1 para 2 → checksum quebrado
    expect(ProcessoJudicial::validate('00000014120248010002')->code())->toBe(ErrorCode::INVALID_CHECKSUM);
});

it('rejects processo judicial with invalid tribunal', function (): void {
    // J=1 (STF) possui apenas TR=00; TR=01 não existe → INVALID_REGION
    // Checksum calculado para N=0000001, A=2024, J=1, TR=01, O=0001 → D=26
    expect(ProcessoJudicial::validate('00000012620241010001')->code())->toBe(ErrorCode::INVALID_REGION);
});

it('rejects processo judicial with non-existent military state tribunal', function (): void {
    // J=9 (Militar Estadual) só tem TR=13 (MG), TR=21 (RS) e TR=26 (SP)
    // N=0000001, A=2024, J=9, TR=01, O=0001 → D=57 (checksum válido, mas TR inválido)
    expect(ProcessoJudicial::validate('00000015720249010001')->code())->toBe(ErrorCode::INVALID_REGION);
});

it('generates a valid processo judicial', function (): void {
    $value = ProcessoJudicial::generate();
    expect(ProcessoJudicial::isValid($value))->toBeTrue();
});

it('masks a processo judicial correctly', function (string $input, string $expected): void {
    expect(ProcessoJudicial::mask($input))->toBe($expected);
})->with([
    ['00000014120248010001', '0000001-41.2024.8.01.0001'],
    ['0000001-41.2024.8.01.0001', '0000001-41.2024.8.01.0001'],
]);

it('mask returns input unchanged when length is invalid', function (): void {
    expect(ProcessoJudicial::mask('1234'))->toBe('1234');
});

it('generated value masks to expected CNJ format', function (): void {
    $value  = ProcessoJudicial::generate();
    $masked = ProcessoJudicial::mask($value);
    expect($masked)->toMatch('/^\d{7}-\d{2}\.\d{4}\.\d\.\d{2}\.\d{4}$/');
});
