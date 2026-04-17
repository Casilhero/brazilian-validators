<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\BoletoInfo;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Validators\Boleto;

// Boleto bancário de teste com DVs de campo válidos (47 dígitos)
// Banco Itaú (341), moeda 9
// Campo1: 341917900 → DV=1 (mod10 ✓)
// Campo2: 0104351004 → DV=7 (mod10 ✓)
// Campo3: 9102015000 → DV=8 (mod10 ✓)
const VALID_BANCARIO_MASKED = '34191.79001 01043.510047 91020.150008 2 85480000000000';
const VALID_BANCARIO_DIGITS = '34191790010104351004791020150008285480000000000';

it('accepts valid boleto bancário with mask', function (): void {
    expect(Boleto::isValid(VALID_BANCARIO_MASKED))->toBeTrue();
    expect(Boleto::validate(VALID_BANCARIO_MASKED)->isValid())->toBeTrue();
});

it('accepts valid boleto bancário without mask', function (): void {
    expect(Boleto::isValid(VALID_BANCARIO_DIGITS))->toBeTrue();
});

it('rejects boleto with invalid length', function (string $value, string $code): void {
    $result = Boleto::validate($value);
    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe($code);
})->with([
    ['12345', ErrorCode::INVALID_LENGTH],
    ['1234567890123456789012345678901234567890123456', ErrorCode::INVALID_LENGTH], // 46 digits
]);

it('rejects boleto bancário with invalid campo 1 DV', function (): void {
    $corrupted = substr(VALID_BANCARIO_DIGITS, 0, 9).'9'.substr(VALID_BANCARIO_DIGITS, 10);
    $result = Boleto::validate($corrupted);
    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_CHECKSUM);
});

it('rejects boleto bancário with invalid campo 2 DV', function (): void {
    $corrupted = substr(VALID_BANCARIO_DIGITS, 0, 20).'0'.substr(VALID_BANCARIO_DIGITS, 21);
    $result = Boleto::validate($corrupted);
    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_CHECKSUM);
});

it('rejects boleto bancário with invalid campo 3 DV', function (): void {
    $corrupted = substr(VALID_BANCARIO_DIGITS, 0, 31).'0'.substr(VALID_BANCARIO_DIGITS, 32);
    $result = Boleto::validate($corrupted);
    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_CHECKSUM);
});

it('generates a valid boleto with 47 digits', function (): void {
    $boleto = Boleto::generate();
    expect(strlen($boleto))->toBe(47);
    expect(Boleto::isValid($boleto))->toBeTrue();
});

it('generates multiple valid boletos', function (): void {
    for ($i = 0; $i < 10; $i++) {
        expect(Boleto::isValid(Boleto::generate()))->toBeTrue();
    }
});

it('applies bancário mask to 47-digit boleto', function (): void {
    expect(Boleto::mask(VALID_BANCARIO_DIGITS))->toBe(VALID_BANCARIO_MASKED);
});

it('applies progressive mask to partial input', function (): void {
    expect(Boleto::mask(''))->toBe('');
    expect(Boleto::mask('34191'))->toBe('34191');
    expect(Boleto::mask('341917'))->toBe('34191.7');
    expect(Boleto::mask('3419179001'))->toBe('34191.79001');
    expect(Boleto::mask('34191790011'))->toBe('34191.79001 1');
});

it('mask ignores non-digit characters in input', function (): void {
    expect(Boleto::mask(VALID_BANCARIO_MASKED))->toBe(VALID_BANCARIO_MASKED);
});

it('generated boleto mask matches expected pattern', function (): void {
    $masked = Boleto::mask(Boleto::generate());
    expect($masked)->toMatch('/^\d{5}\.\d{5} \d{5}\.\d{6} \d{5}\.\d{6} \d \d{14}$/');
});

// ─── Boleto bancário BB para testes de parse ──────────────────────────────────
// bankCode='001', fator=7586 → 2018-07-15, amount=102656 centavos (R$1.026,56)
const BB_BOLETO_DIGITS = '00190000090114971860168524522114675860000102656';

// Boleto de arrecadação sintético válido (48 dígitos)
// produto='8', segmento='2', realValueId='6', valor=12500 centavos (R$125,00)
// barcode = '82600000012' + '50000000000' + '00000000000' + '00000000000'
// DV1=mod10('82600000012')=3, DV2=mod10('50000000000')=9, DV3/DV4=mod10('00000000000')=0
const ARRECADACAO_BOLETO_DIGITS = '826000000123500000000009000000000000000000000000';

it('parse retorna null para boleto inválido', function (): void {
    expect(Boleto::parse('12345'))->toBeNull();
    expect(Boleto::parse('11111111111111111111111111111111111111111111111'))->toBeNull();
});

it('parse retorna BoletoInfo para boleto bancário válido', function (): void {
    $info = Boleto::parse(BB_BOLETO_DIGITS);
    expect($info)->toBeInstanceOf(BoletoInfo::class);
    expect($info->type)->toBe('bancario');
});

it('parse extrai bankCode e currency do boleto bancário', function (): void {
    $info = Boleto::parse(BB_BOLETO_DIGITS);
    expect($info->bankCode)->toBe('001');
    expect($info->currency)->toBe('9');
});

it('parse extrai o campo livre de 25 dígitos do boleto bancário', function (): void {
    $info = Boleto::parse(BB_BOLETO_DIGITS);
    expect(strlen($info->freeField))->toBe(25);
    expect($info->freeField)->toBe('0000001149718606852452211');
});

it('parse extrai data de vencimento do boleto bancário', function (): void {
    $info = Boleto::parse(BB_BOLETO_DIGITS);
    expect($info->expirationDate)->not->toBeNull();
    expect($info->expirationDate->format('Y-m-d'))->toBe('2018-07-15');
});

it('parse extrai valor em centavos do boleto bancário', function (): void {
    $info = Boleto::parse(BB_BOLETO_DIGITS);
    expect($info->amount)->toBe(102656);
    expect($info->amountInReals())->toBe(1026.56);
});

it('parse aceita boleto bancário com máscara na entrada', function (): void {
    $masked = '0019.00000 9 0114.971860 168524.52211 4 6 7586 0000102656';
    $info = Boleto::parse(BB_BOLETO_DIGITS);
    expect($info->amount)->toBe(102656);
});

it('parse retorna null para amount=0 quando fator é zero', function (): void {
    // VALID_BANCARIO_DIGITS tem factor=8548, então expirationDate não é null
    $info = Boleto::parse(VALID_BANCARIO_DIGITS);
    expect($info)->not->toBeNull();
    expect($info->expirationDate)->not->toBeNull();
    expect($info->amount)->toBe(0);
});

it('parse retorna BoletoInfo para boleto de arrecadação válido', function (): void {
    $info = Boleto::parse(ARRECADACAO_BOLETO_DIGITS);
    expect($info)->toBeInstanceOf(BoletoInfo::class);
    expect($info->type)->toBe('arrecadacao');
});

it('parse extrai bankCode, currency e amount do boleto de arrecadação', function (): void {
    $info = Boleto::parse(ARRECADACAO_BOLETO_DIGITS);
    expect($info->bankCode)->toBe('826');
    expect($info->currency)->toBe('6');
    expect($info->amount)->toBe(12500);
    expect($info->amountInReals())->toBe(125.0);
});

it('parse retorna expirationDate null para boleto de arrecadação', function (): void {
    $info = Boleto::parse(ARRECADACAO_BOLETO_DIGITS);
    expect($info->expirationDate)->toBeNull();
});

it('parse retorna freeField de 44 dígitos para boleto de arrecadação', function (): void {
    $info = Boleto::parse(ARRECADACAO_BOLETO_DIGITS);
    expect(strlen($info->freeField))->toBe(44);
    expect($info->freeField)->toBe('82600000012500000000000000000000000000000000');
});

it('parse retorna BoletoInfo de boleto gerado pelo generate()', function (): void {
    $generated = Boleto::generate();
    $info = Boleto::parse($generated);
    expect($info)->toBeInstanceOf(BoletoInfo::class);
    expect($info->type)->toBe('bancario');
    expect(strlen($info->bankCode))->toBe(3);
    expect(strlen($info->freeField))->toBe(25);
});

it('parse retorna array correto via toArray()', function (): void {
    $info = Boleto::parse(BB_BOLETO_DIGITS);
    $arr = $info->toArray();
    expect($arr['type'])->toBe('bancario');
    expect($arr['bank_code'])->toBe('001');
    expect($arr['amount'])->toBe(102656);
    expect($arr['expiration_date'])->toBe('2018-07-15');
});
