<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Validators;

use Casilhero\BrazilianValidators\Support\ErrorCode;
use Casilhero\BrazilianValidators\Support\Uf;
use Casilhero\BrazilianValidators\Support\ValidationResult;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualAc;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualAl;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualAm;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualAp;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualBa;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualCe;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualDf;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualEs;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualGo;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualMa;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualMg;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualMs;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualMt;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualPa;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualPb;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualPe;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualPi;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualPr;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualRj;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualRn;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualRo;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualRr;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualRs;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualSc;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualSe;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualSp;
use Casilhero\BrazilianValidators\Validators\InscricaoEstadual\InscricaoEstadualTo;

final class InscricaoEstadual
{
    public static function isValid(string $value, string|Uf $uf): bool
    {
        return self::validate($value, $uf)->isValid();
    }

    public static function validate(string $value, string|Uf $uf): ValidationResult
    {
        if (!$uf instanceof Uf) {
            $uf = Uf::tryFrom(strtoupper($uf));

            if ($uf === null) {
                return ValidationResult::invalid(ErrorCode::INVALID_REGION);
            }
        }

        $ie = strtoupper((string) preg_replace('/[.\-\/\s]/', '', $value));

        return match ($uf) {
            Uf::AC => InscricaoEstadualAc::validate($ie),
            Uf::AL => InscricaoEstadualAl::validate($ie),
            Uf::AP => InscricaoEstadualAp::validate($ie),
            Uf::AM => InscricaoEstadualAm::validate($ie),
            Uf::BA => InscricaoEstadualBa::validate($ie),
            Uf::CE => InscricaoEstadualCe::validate($ie),
            Uf::DF => InscricaoEstadualDf::validate($ie),
            Uf::ES => InscricaoEstadualEs::validate($ie),
            Uf::GO => InscricaoEstadualGo::validate($ie),
            Uf::MA => InscricaoEstadualMa::validate($ie),
            Uf::MT => InscricaoEstadualMt::validate($ie),
            Uf::MS => InscricaoEstadualMs::validate($ie),
            Uf::MG => InscricaoEstadualMg::validate($ie),
            Uf::PA => InscricaoEstadualPa::validate($ie),
            Uf::PB => InscricaoEstadualPb::validate($ie),
            Uf::PR => InscricaoEstadualPr::validate($ie),
            Uf::PE => InscricaoEstadualPe::validate($ie),
            Uf::PI => InscricaoEstadualPi::validate($ie),
            Uf::RJ => InscricaoEstadualRj::validate($ie),
            Uf::RN => InscricaoEstadualRn::validate($ie),
            Uf::RS => InscricaoEstadualRs::validate($ie),
            Uf::RO => InscricaoEstadualRo::validate($ie),
            Uf::RR => InscricaoEstadualRr::validate($ie),
            Uf::SC => InscricaoEstadualSc::validate($ie),
            Uf::SP => InscricaoEstadualSp::validate($ie),
            Uf::SE => InscricaoEstadualSe::validate($ie),
            Uf::TO => InscricaoEstadualTo::validate($ie),
        };
    }

}
