<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Uf;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual;

describe('InscricaoEstadual::validate', function () {
    it('retorna inválido para UF desconhecida', function () {
        $result = InscricaoEstadual::validate('123456789', 'XX');
        expect($result->isValid())->toBeFalse();
        expect($result->code())->toBe(ErrorCode::INVALID_REGION);
    });

    it('aceita instância de Uf como segundo parâmetro', function () {
        expect(InscricaoEstadual::isValid('110042490114', Uf::SP))->toBeTrue();
    });

    it('normaliza separadores antes de validar', function () {
        // SP com pontos: 110.042.490.114
        expect(InscricaoEstadual::isValid('110.042.490.114', 'SP'))->toBeTrue();
    });

    // AC
    it('valida IE do AC corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'AC'))->toBe($expected);
    })->with([
        ['0100482300112', true],
        ['0100482300113', false],
        ['010048230011', false],   // comprimento errado
        ['0200482300114', false],  // prefixo errado
    ]);

    // AL
    it('valida IE do AL corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'AL'))->toBe($expected);
    })->with([
        ['240000048', true],
        ['240000049', false],
        ['12345678', false], // comprimento errado
        ['250000048', false], // prefixo errado
    ]);

    // AP
    it('valida IE do AP corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'AP'))->toBe($expected);
    })->with([
        ['030123459', true],
        ['030123450', false],
        ['12345678', false], // comprimento errado
    ]);

    // AM
    it('valida IE do AM corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'AM'))->toBe($expected);
    })->with([
        ['999999990', true],
        ['999999999', false],
        ['12345678', false], // comprimento errado
    ]);

    // BA
    it('valida IE do BA corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'BA'))->toBe($expected);
    })->with([
        ['12345663', true],
        ['12345664', false],
        ['1234567', false],   // comprimento errado
        // formato de 9 dígitos (exemplo oficial do estado): 1000003-06
        ['100000306', true],
        ['100000307', false], // 2º DV errado
        ['100000316', false], // 1º DV errado
    ]);

    // CE
    it('valida IE do CE corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'CE'))->toBe($expected);
    })->with([
        ['060000015', true],
        ['060000016', false],
        ['12345678', false], // comprimento errado
    ]);

    // DF
    it('valida IE do DF corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'DF'))->toBe($expected);
    })->with([
        ['0730000100109', true],
        ['0730000100108', false],
        ['073000010010', false],  // comprimento errado
        ['0830000100109', false], // prefixo errado
    ]);

    // ES
    it('valida IE do ES corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'ES'))->toBe($expected);
    })->with([
        ['999999990', true],
        ['999999994', false],
        ['12345678', false], // comprimento errado
    ]);

    // GO
    it('valida IE do GO corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'GO'))->toBe($expected);
    })->with([
        ['109876547', true],
        ['109876548', false],
        ['110944020', true],  // resto=1, DV=0
        ['110944021', false], // DV=1 inválido (resto=1 → DV=0)
        ['209876549', true],  // prefixo '20' válido
        ['209876547', false], // checksum errado
        ['150000001', false], // prefixo '15' inválido para GO
        ['12345678', false],  // comprimento errado
    ]);

    // MA
    it('valida IE do MA corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'MA'))->toBe($expected);
    })->with([
        ['120000385', true],
        ['120000386', false],
        ['12345678', false], // comprimento errado
        ['110000385', false], // prefixo errado
    ]);

    // MT
    it('valida IE do MT corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'MT'))->toBe($expected);
    })->with([
        ['00130000019', true],
        ['00130000016', false],
        ['1234567890', false], // comprimento errado
    ]);

    // MS
    it('valida IE do MS corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'MS'))->toBe($expected);
    })->with([
        ['283115947', true],
        ['283115949', false],
        ['500000000', true],  // prefixo '50' válido, DV=0
        ['500000001', false], // DV errado
        ['12345678', false],  // comprimento errado
        ['293115947', false], // prefixo errado
    ]);

    // MG
    it('valida IE do MG corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'MG'))->toBe($expected);
    })->with([
        ['0623079040081', true],
        ['0623079040085', false],
        ['062307904008', false], // comprimento errado
    ]);

    // PA
    it('valida IE do PA corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'PA'))->toBe($expected);
    })->with([
        ['159999995', true],
        ['159999996', false],
        ['750000023', true],  // prefixo '75' válido (exemplo 2 do doc oficial)
        ['750000024', false], // DV errado
        ['12345678', false],  // comprimento errado
        ['169999995', false], // prefixo '16' inválido para PA
    ]);

    // PB
    it('valida IE do PB corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'PB'))->toBe($expected);
    })->with([
        ['060000015', true],
        ['060000016', false],
        ['12345678', false], // comprimento errado
    ]);

    // PR
    it('valida IE do PR corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'PR'))->toBe($expected);
    })->with([
        ['1234567850', true],
        ['1234567851', false],
        ['123456785', false], // comprimento errado
    ]);

    // PE (9 dígitos — formato antigo)
    it('valida IE do PE no formato antigo (9 dígitos)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'PE'))->toBe($expected);
    })->with([
        ['032141840', true],
        ['032141843', false],
        ['12345678', false], // comprimento errado
    ]);

    // PE (14 dígitos — formato novo)
    it('valida IE do PE no formato novo (14 dígitos)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'PE'))->toBe($expected);
    })->with([
        ['18100100000049', true],
        ['18100100000048', false],
    ]);

    // PI
    it('valida IE do PI corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'PI'))->toBe($expected);
    })->with([
        ['012345679', true],
        ['012345678', false],
        ['12345678', false], // comprimento errado
    ]);

    // RJ
    it('valida IE do RJ corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'RJ'))->toBe($expected);
    })->with([
        ['99999993', true],
        ['99999996', false],
        ['1234567', false], // comprimento errado
    ]);

    // RN (9 dígitos)
    it('valida IE do RN no formato curto (9 dígitos)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'RN'))->toBe($expected);
    })->with([
        ['200400401', true],
        ['200400402', false],
        ['12345678', false],  // comprimento errado
        ['100400401', false], // prefixo inválido
    ]);

    // RN (10 dígitos)
    it('valida IE do RN no formato longo (10 dígitos)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'RN'))->toBe($expected);
    })->with([
        ['2000400400', true],
        ['2000400401', false],
        ['1000400400', false], // prefixo inválido
    ]);

    // RS
    it('valida IE do RS corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'RS'))->toBe($expected);
    })->with([
        ['2243658792', true],
        ['2243658793', false],
        ['123456789', false], // comprimento errado
    ]);

    // RO (9 dígitos — formato antigo)
    it('valida IE do RO no formato antigo (9 dígitos)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'RO'))->toBe($expected);
    })->with([
        ['101625213', true],
        ['101625214', false],
        ['12345678', false], // comprimento errado
    ]);

    // RO (14 dígitos — formato novo)
    it('valida IE do RO no formato novo (14 dígitos)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'RO'))->toBe($expected);
    })->with([
        ['00000000625213', true],
        ['00000000625214', false],
    ]);

    // RR
    it('valida IE do RR corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'RR'))->toBe($expected);
    })->with([
        ['240066281', true],
        ['240066282', false],
        ['12345678', false], // comprimento errado
        ['250066281', false], // prefixo errado
    ]);

    // SC
    it('valida IE do SC corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'SC'))->toBe($expected);
    })->with([
        ['251040852', true],
        ['251040854', false],
        ['000003000', true],  // resto=1 → DV=0
        ['000003001', false], // DV errado
        ['000000051', true],  // resto=10 → DV=1
        ['000000050', false], // DV errado (resto=10 não vira 0)
        ['12345678', false],  // comprimento errado
    ]);

    // SP (12 dígitos — padrão)
    it('valida IE do SP no formato padrão (12 dígitos)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'SP'))->toBe($expected);
    })->with([
        ['110042490114', true],
        ['110042490115', false],
        ['11004249011', false], // comprimento errado
    ]);

    // SP (13 chars — produtor rural com prefixo P)
    it('valida IE do SP para produtor rural (prefixo P)', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'SP'))->toBe($expected);
    })->with([
        ['P010010203000', true],
        ['P010010204000', false], // DV errado (posição 9)
        ['P01001020300', false],  // comprimento errado
    ]);

    // SE
    it('valida IE do SE corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'SE'))->toBe($expected);
    })->with([
        ['271234563', true],
        ['271234564', false],
        ['12345678', false], // comprimento errado
    ]);

    // TO
    it('valida IE do TO corretamente', function (string $ie, bool $expected) {
        expect(InscricaoEstadual::isValid($ie, 'TO'))->toBe($expected);
    })->with([
        ['29010227836', true],
        ['29010227835', false],
        ['1234567890', false], // comprimento errado
        ['29040227836', false], // prefixo inválido
    ]);
});
