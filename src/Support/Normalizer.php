<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Support;

final class Normalizer
{
    public static function digits(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    public static function isRepeatedDigits(string $digits): bool
    {
        return $digits !== '' && (bool) preg_match('/^(\d)\1+$/', $digits);
    }
}
