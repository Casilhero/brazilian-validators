<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\BrazilianValidator;
use Casilhero\BrazilianValidators\Support\ErrorCode;

it('supports facade style access in core package', function (): void {
    expect(BrazilianValidator::cpf('529.982.247-25'))->toBeTrue();
    expect(BrazilianValidator::cnpj('04.252.011/0001-10'))->toBeTrue();

    $result = BrazilianValidator::cpfResult('11111111111');

    expect($result->isValid())->toBeFalse();
    expect($result->code())->toBe(ErrorCode::INVALID_FORMAT);
});
