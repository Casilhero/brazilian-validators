<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\TituloEleitor;

function mod11TituloEleitor(int $sum, bool $spMg = false): int
{
    $mod = $sum % 11;

    if ($mod === 10) {
        return 0;
    }

    if ($mod === 0) {
        return $spMg ? 1 : 0;
    }

    return $mod;
}

function makeTituloEleitor(string $base): string
{
    // base: 10 chars (seq 8 + UF 2) ou 11 chars (seq 9 + UF 2)
    $seqLen = strlen($base) - 2;
    $uf     = (int) substr($base, $seqLen, 2);
    $spMg   = $uf === 1 || $uf === 2;

    // Pesos direita→esquerda, ciclo 9→2→9 (idem ao validator)
    $sumA = 0;
    $mult = 9;

    for ($i = $seqLen - 1; $i >= 0; $i--) {
        $sumA += (int) $base[$i] * $mult;
        $mult--;
        if ($mult < 2) {
            $mult = 9;
        }
    }

    $dv1 = mod11TituloEleitor($sumA, $spMg);

    $sumB = (int) $base[$seqLen] * 7 + (int) $base[$seqLen + 1] * 8 + $dv1 * 9;

    $dv2 = mod11TituloEleitor($sumB, $spMg);

    return $base . (string) $dv1 . (string) $dv2;
}

it('accepts valid titulo eleitor values', function (): void {
    $valid = makeTituloEleitor('1234567801');

    expect(TituloEleitor::isValid($valid))->toBeTrue();
});

it('accepts titulo eleitor with different uf codes', function (): void {
    // UF 01 (SP) and UF 28 (max valid)
    expect(TituloEleitor::isValid(makeTituloEleitor('1234567801')))->toBeTrue();
    expect(TituloEleitor::isValid(makeTituloEleitor('1234567828')))->toBeTrue();
});

it('rejects titulo eleitor with invalid uf', function (): void {
    // UF 00
    $invalidUf00 = makeTituloEleitor('1234567800');
    expect(TituloEleitor::validate($invalidUf00)->code())->toBe(ErrorCode::INVALID_REGION);

    // UF 29
    $invalidUf29 = makeTituloEleitor('1234567829');
    expect(TituloEleitor::validate($invalidUf29)->code())->toBe(ErrorCode::INVALID_REGION);
});

it('rejects invalid titulo eleitor values', function (): void {
    $valid = makeTituloEleitor('1234567801');
    $invalidChecksum = substr($valid, 0, 11) . ((string) (((int) $valid[11] + 1) % 10));

    expect(TituloEleitor::validate($invalidChecksum)->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect(TituloEleitor::validate('123')->code())->toBe(ErrorCode::INVALID_LENGTH);
    expect(TituloEleitor::validate('12345678901')->code())->toBe(ErrorCode::INVALID_LENGTH);   // 11 dígitos
    expect(TituloEleitor::validate('12345678901234')->code())->toBe(ErrorCode::INVALID_LENGTH); // 14 dígitos
});

// ------- Títulos de 13 dígitos (sequencial de 9 dígitos, emitidos em SP/MG) -------
//
// base `12345678901` → seq=`123456789`, UF=01 (SP)
// sumA (direita→esquerda): 9*9+8*8+7*7+6*6+5*5+4*4+3*3+2*2+1*9
//       = 81+64+49+36+25+16+9+4+9 = 293 → 293%11=7 → dv1=7
// sumB  = 0*7+1*8+7*9 = 71 → 71%11=5 → dv2=5
// Título completo: 1234567890175
it('accepts valid 13-digit titulo eleitor (9-digit sequential, SP/MG)', function (): void {
    $valid = makeTituloEleitor('12345678901'); // base de 11 chars

    expect(strlen($valid))->toBe(13);
    expect(TituloEleitor::isValid($valid))->toBeTrue();
});

it('rejects 13-digit titulo eleitor with wrong checksum', function (): void {
    $valid   = makeTituloEleitor('12345678901');
    $tampered = substr($valid, 0, 12) . ((string) (((int) $valid[12] + 1) % 10));

    expect(TituloEleitor::validate($tampered)->code())->toBe(ErrorCode::INVALID_CHECKSUM);
});

// Sequencial 10000001 + UF 01 (SP): sumA = 1*2 + 1*9 = 11 → resto 0
// → exceção SP/MG: DV1=1, sumB=0*7+1*8+1*9=17 → DV2=6 → número válido: 100000010116
// Sem a exceção, seria tratado como DV1=0 → 100000010108, que deve ser inválido para SP.
it('accepts titulo eleitor from SP/MG when remainder is zero (exception mod=0 -> 1)', function (): void {
    // Título gerado com a exceção SP (UF=01): DV1=1, DV2=6
    expect(TituloEleitor::isValid('100000010116'))->toBeTrue();

    // Título gerado sem a exceção (DV1=0, DV2=8) deve ser rejeitado
    expect(TituloEleitor::validate('100000010108')->code())->toBe(ErrorCode::INVALID_CHECKSUM);
});

it('accepts titulo eleitor from MG (uf 02) with zero remainder exception', function (): void {
    // Mesmo sequencial 10000001, mas UF=02 (MG)
    // sumA=11 → mod=0 → dv1=1; sumB=0*7+2*8+1*9=25 → mod=3 → dv2=3
    expect(TituloEleitor::isValid('100000010213'))->toBeTrue();
});

it('generates a valid titulo eleitor', function (): void {
    expect(TituloEleitor::isValid(TituloEleitor::generate()))->toBeTrue();
});

it('masks a titulo eleitor correctly', function (): void {
    // 12 dígitos
    expect(TituloEleitor::mask('123456780191'))->toBe('1234 5678 0191');
    // 13 dígitos (SP/MG)
    expect(TituloEleitor::mask('1234567890175'))->toBe('12345 6789 0175');
});

it('generates a masked titulo eleitor in correct format', function (): void {
    expect(TituloEleitor::mask(TituloEleitor::generate()))->toMatch('/^\d{4,5} \d{4} \d{4}$/');
});
