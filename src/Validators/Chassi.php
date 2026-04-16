<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Contracts\BrazilianValidatorContract;
use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\ValidationResult;

final class Chassi implements BrazilianValidatorContract
{
    private const CHAR_VALUES = [
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

    private const WEIGHTS = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];

    public static function isValid(string $value): bool
    {
        return self::validate($value)->isValid();
    }

    public static function validate(string $value): ValidationResult
    {
        $vin = strtoupper((string) preg_replace('/[\s\-]/', '', $value));

        if (strlen($vin) !== 17) {
            return ValidationResult::invalid(ErrorCode::INVALID_LENGTH);
        }

        if (preg_match('/[IOQ]/', $vin) === 1) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        if (preg_match('/^\d{6}$/', substr($vin, 11, 6)) !== 1) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        if (preg_match('/^(.)\1{16}$/', $vin) === 1) {
            return ValidationResult::invalid(ErrorCode::INVALID_FORMAT);
        }

        $sum = 0;

        for ($i = 0; $i < 17; $i++) {
            $char = $vin[$i];
            $charValue = is_numeric($char) ? (int) $char : (self::CHAR_VALUES[$char] ?? 0);
            $sum += $charValue * self::WEIGHTS[$i];
        }

        $mod = $sum % 11;
        $expected = $mod === 10 ? 'X' : (string) $mod;

        if ($vin[8] !== $expected) {
            return ValidationResult::invalid(ErrorCode::INVALID_CHECKSUM);
        }

        return ValidationResult::valid();
    }

    public static function generate(): string
    {
        // Chars allowed in VIN (no I, O, Q)
        $chars = 'ABCDEFGHJKLMNPRSTUVWXYZ0123456789';
        $len   = strlen($chars) - 1;

        // WMI: TST (manufacturer test code)
        $vin = 'TST';

        // VDS positions 3-7: 5 random chars
        for ($i = 0; $i < 5; $i++) {
            $vin .= $chars[random_int(0, $len)];
        }

        // Position 8 placeholder (check digit, will be replaced)
        $vin .= '0';

        // VIS positions 9-10: 2 random chars
        for ($i = 0; $i < 2; $i++) {
            $vin .= $chars[random_int(0, $len)];
        }

        // Positions 11-16: 6 numeric digits
        for ($i = 0; $i < 6; $i++) {
            $vin .= (string) random_int(0, 9);
        }

        // Calculate check digit (position 8, weight=0 in WEIGHTS, so it contributes 0)
        $sum = 0;
        for ($i = 0; $i < 17; $i++) {
            $char      = $vin[$i];
            $charValue = is_numeric($char) ? (int) $char : (self::CHAR_VALUES[$char] ?? 0);
            $sum += $charValue * self::WEIGHTS[$i];
        }
        $mod        = $sum % 11;
        $checkDigit = $mod === 10 ? 'X' : (string) $mod;

        return substr($vin, 0, 8) . $checkDigit . substr($vin, 9);
    }

    public static function mask(string $value): string
    {
        return strtoupper((string) preg_replace('/[\s\-]/', '', $value));
    }
}
