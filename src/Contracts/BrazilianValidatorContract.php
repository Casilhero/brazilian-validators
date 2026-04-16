<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Contracts;

use Casilhero\BrazilianValidators\Support\ValidationResult;

interface BrazilianValidatorContract
{
    public static function isValid(string $value): bool;

    public static function validate(string $value): ValidationResult;

    public static function generate(): string;

    public static function mask(string $value): string;
}
