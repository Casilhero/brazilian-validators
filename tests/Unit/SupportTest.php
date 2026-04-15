<?php

declare(strict_types=1);

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

it('provides access to validation result fields', function (): void {
    $invalid = ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM, ['field' => 'cnpj']);

    expect($invalid->isValid())->toBeFalse();
    expect($invalid->code())->toBe(ErrorCode::INVALID_CHECKSUM);
    expect($invalid->context())->toBe(['field' => 'cnpj']);

    $valid = ValidationResult::valid();

    expect($valid->isValid())->toBeTrue();
    expect($valid->code())->toBeNull();
    expect($valid->context())->toBe([]);
});

it('exposes error code constants', function (): void {
    expect(ErrorCode::INVALID_LENGTH)->toBe('invalid_length');
    expect(ErrorCode::INVALID_FORMAT)->toBe('invalid_format');
    expect(ErrorCode::INVALID_CHECKSUM)->toBe('invalid_checksum');
    expect(ErrorCode::INVALID_REGION)->toBe('invalid_region');
    expect(ErrorCode::INVALID_PREFIX)->toBe('invalid_prefix');
});
