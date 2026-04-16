<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Contracts;

use Casilhero\BrazilianValidators\Support\Uf;
use Casilhero\BrazilianValidators\Support\ValidationResult;

interface BrazilianValidatorWithUfContract
{
    public static function isValid(string $value, string|Uf $uf): bool;

    public static function validate(string $value, string|Uf $uf): ValidationResult;

    public static function generate(string|Uf $uf): string;

    public static function mask(string $value, string|Uf $uf): string;
}
