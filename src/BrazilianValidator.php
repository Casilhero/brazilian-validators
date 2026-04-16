<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators;

use Casilhero\BrazilianValidators\Support\CertidaoInfo;
use Casilhero\BrazilianValidators\Support\Uf;
use Casilhero\BrazilianValidators\Support\ValidationResult;
use Casilhero\BrazilianValidators\Validators\Caepf;
use Casilhero\BrazilianValidators\Validators\Certidao;
use Casilhero\BrazilianValidators\Validators\Chassi;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual;
use Casilhero\BrazilianValidators\Validators\Cnh;
use Casilhero\BrazilianValidators\Validators\Cnpj;
use Casilhero\BrazilianValidators\Validators\Renavam;
use Casilhero\BrazilianValidators\Validators\TituloEleitor;
use Casilhero\BrazilianValidators\Validators\Cns;
use Casilhero\BrazilianValidators\Validators\Cpf;
use Casilhero\BrazilianValidators\Validators\CpfCnpj;
use Casilhero\BrazilianValidators\Validators\NisPis;
use Casilhero\BrazilianValidators\Validators\Phone;
use Casilhero\BrazilianValidators\Validators\PhoneDdi;
use Casilhero\BrazilianValidators\Validators\Passaporte;
use Casilhero\BrazilianValidators\Validators\ProcessoJudicial;
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

    public static function renavam(string $value): bool
    {
        return Renavam::isValid($value);
    }

    public static function renavamResult(string $value): ValidationResult
    {
        return Renavam::validate($value);
    }

    public static function tituloEleitor(string $value): bool
    {
        return TituloEleitor::isValid($value);
    }

    public static function tituloEleitorResult(string $value): ValidationResult
    {
        return TituloEleitor::validate($value);
    }

    public static function chassi(string $value): bool
    {
        return Chassi::isValid($value);
    }

    public static function chassiResult(string $value): ValidationResult
    {
        return Chassi::validate($value);
    }

    public static function inscricaoEstadual(string $value, string|Uf $uf): bool
    {
        return InscricaoEstadual::isValid($value, $uf);
    }

    public static function inscricaoEstadualResult(string $value, string|Uf $uf): ValidationResult
    {
        return InscricaoEstadual::validate($value, $uf);
    }

    public static function certidao(string $value): bool
    {
        return Certidao::isValid($value);
    }

    public static function certidaoResult(string $value): ValidationResult
    {
        return Certidao::validate($value);
    }

    public static function certidaoParse(string $value): ?CertidaoInfo
    {
        return Certidao::parse($value);
    }

    public static function passaporte(string $value): bool
    {
        return Passaporte::isValid($value);
    }

    public static function passaporteResult(string $value): ValidationResult
    {
        return Passaporte::validate($value);
    }

    public static function caepf(string $value): bool
    {
        return Caepf::isValid($value);
    }

    public static function caepfResult(string $value): ValidationResult
    {
        return Caepf::validate($value);
    }

    public static function processoJudicial(string $value): bool
    {
        return ProcessoJudicial::isValid($value);
    }

    public static function processoJudicialResult(string $value): ValidationResult
    {
        return ProcessoJudicial::validate($value);
    }

    // ─── generate ────────────────────────────────────────────────────────────

    public static function cpfGenerate(): string
    {
        return Cpf::generate();
    }

    public static function cnpjGenerate(): string
    {
        return Cnpj::generate();
    }

    public static function cpfCnpjGenerate(): string
    {
        return CpfCnpj::generate();
    }

    public static function cnhGenerate(): string
    {
        return Cnh::generate();
    }

    public static function cnsGenerate(): string
    {
        return Cns::generate();
    }

    public static function nisPisGenerate(): string
    {
        return NisPis::generate();
    }

    public static function phoneGenerate(): string
    {
        return Phone::generate();
    }

    public static function phoneDdiGenerate(): string
    {
        return PhoneDdi::generate();
    }

    public static function renavamGenerate(): string
    {
        return Renavam::generate();
    }

    public static function suframaGenerate(): string
    {
        return Suframa::generate();
    }

    public static function certidaoGenerate(): string
    {
        return Certidao::generate();
    }

    public static function passaporteGenerate(): string
    {
        return Passaporte::generate();
    }

    public static function caepfGenerate(): string
    {
        return Caepf::generate();
    }

    public static function chassiGenerate(): string
    {
        return Chassi::generate();
    }

    public static function tituloEleitorGenerate(): string
    {
        return TituloEleitor::generate();
    }

    public static function processoJudicialGenerate(): string
    {
        return ProcessoJudicial::generate();
    }

    // ─── mask ────────────────────────────────────────────────────────────────

    public static function cpfMask(string $value): string
    {
        return Cpf::mask($value);
    }

    public static function cnpjMask(string $value): string
    {
        return Cnpj::mask($value);
    }

    public static function cpfCnpjMask(string $value): string
    {
        return CpfCnpj::mask($value);
    }

    public static function cnhMask(string $value): string
    {
        return Cnh::mask($value);
    }

    public static function cnsMask(string $value): string
    {
        return Cns::mask($value);
    }

    public static function nisPisMask(string $value): string
    {
        return NisPis::mask($value);
    }

    public static function phoneMask(string $value): string
    {
        return Phone::mask($value);
    }

    public static function phoneDdiMask(string $value): string
    {
        return PhoneDdi::mask($value);
    }

    public static function renavamMask(string $value): string
    {
        return Renavam::mask($value);
    }

    public static function suframaMask(string $value): string
    {
        return Suframa::mask($value);
    }

    public static function certidaoMask(string $value): string
    {
        return Certidao::mask($value);
    }

    public static function passaporteMask(string $value): string
    {
        return Passaporte::mask($value);
    }

    public static function caepfMask(string $value): string
    {
        return Caepf::mask($value);
    }

    public static function chassiMask(string $value): string
    {
        return Chassi::mask($value);
    }

    public static function tituloEleitorMask(string $value): string
    {
        return TituloEleitor::mask($value);
    }

    public static function processoJudicialMask(string $value): string
    {
        return ProcessoJudicial::mask($value);
    }
}
