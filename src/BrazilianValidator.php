<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators;

use Casilhero\BrazilianValidators\Support\ValidationResult;
use Casilhero\BrazilianValidators\Validators\Cnh;
use Casilhero\BrazilianValidators\Validators\Cnpj;
use Casilhero\BrazilianValidators\Validators\Cns;
use Casilhero\BrazilianValidators\Validators\Cpf;
use Casilhero\BrazilianValidators\Validators\CpfCnpj;
use Casilhero\BrazilianValidators\Validators\NisPis;
use Casilhero\BrazilianValidators\Validators\Phone;
use Casilhero\BrazilianValidators\Validators\PhoneDdi;
use Casilhero\BrazilianValidators\Validators\Suframa;

final class BrazilianValidator
{
    public static function cpf(string $value): bool
    {
        return Cpf::isValid($value);
    }

    public static function cpfResult(string $value): ValidationResult
    {
        return Cpf::validate($value);
    }

    public static function cnpj(string $value): bool
    {
        return Cnpj::isValid($value);
    }

    public static function cnpjResult(string $value): ValidationResult
    {
        return Cnpj::validate($value);
    }

    public static function cpfCnpj(string $value): bool
    {
        return CpfCnpj::isValid($value);
    }

    public static function cpfCnpjResult(string $value): ValidationResult
    {
        return CpfCnpj::validate($value);
    }

    public static function suframa(string $value): bool
    {
        return Suframa::isValid($value);
    }

    public static function suframaResult(string $value): ValidationResult
    {
        return Suframa::validate($value);
    }

    public static function nisPis(string $value): bool
    {
        return NisPis::isValid($value);
    }

    public static function nisPisResult(string $value): ValidationResult
    {
        return NisPis::validate($value);
    }

    public static function phone(string $value): bool
    {
        return Phone::isValid($value);
    }

    public static function phoneResult(string $value): ValidationResult
    {
        return Phone::validate($value);
    }

    public static function phoneDdi(string $value): bool
    {
        return PhoneDdi::isValid($value);
    }

    public static function phoneDdiResult(string $value): ValidationResult
    {
        return PhoneDdi::validate($value);
    }

    public static function cnh(string $value): bool
    {
        return Cnh::isValid($value);
    }

    public static function cnhResult(string $value): ValidationResult
    {
        return Cnh::validate($value);
    }

    public static function cns(string $value): bool
    {
        return Cns::isValid($value);
    }

    public static function cnsResult(string $value): ValidationResult
    {
        return Cns::validate($value);
    }
}
