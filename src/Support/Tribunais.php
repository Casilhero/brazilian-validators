<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Support;

final class Tribunais
{
    private const DATA = [
        1 => [
            0 => ['link' => '', 'nome' => 'Supremo Tribunal Federal'],
        ],
        2 => [
            0 => ['link' => '', 'nome' => 'Conselho Nacional de Justiça'],
        ],
        3 => [
            0 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_stj/_search', 'nome' => 'Superior Tribunal de Justiça'],
        ],
        4 => [
            1 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trf1/_search', 'nome' => 'Tribunal Regional Federal da 1ª Região'],
            2 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trf2/_search', 'nome' => 'Tribunal Regional Federal da 2ª Região'],
            3 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trf3/_search', 'nome' => 'Tribunal Regional Federal da 3ª Região'],
            4 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trf4/_search', 'nome' => 'Tribunal Regional Federal da 4ª Região'],
            5 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trf5/_search', 'nome' => 'Tribunal Regional Federal da 5ª Região'],
            6  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trf6/_search', 'nome' => 'Tribunal Regional Federal da 6ª Região'],
            90 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_cjf/_search', 'nome' => 'Conselho da Justiça Federal'],
        ],
        5 => [
            0  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tst/_search', 'nome' => 'Tribunal Superior do Trabalho'],
            1  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt1/_search', 'nome' => 'Tribunal Regional do Trabalho da 1ª Região'],
            2  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt2/_search', 'nome' => 'Tribunal Regional do Trabalho da 2ª Região'],
            3  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt3/_search', 'nome' => 'Tribunal Regional do Trabalho da 3ª Região'],
            4  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt4/_search', 'nome' => 'Tribunal Regional do Trabalho da 4ª Região'],
            5  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt5/_search', 'nome' => 'Tribunal Regional do Trabalho da 5ª Região'],
            6  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt6/_search', 'nome' => 'Tribunal Regional do Trabalho da 6ª Região'],
            7  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt7/_search', 'nome' => 'Tribunal Regional do Trabalho da 7ª Região'],
            8  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt8/_search', 'nome' => 'Tribunal Regional do Trabalho da 8ª Região'],
            9  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt9/_search', 'nome' => 'Tribunal Regional do Trabalho da 9ª Região'],
            10 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt10/_search', 'nome' => 'Tribunal Regional do Trabalho da 10ª Região'],
            11 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt11/_search', 'nome' => 'Tribunal Regional do Trabalho da 11ª Região'],
            12 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt12/_search', 'nome' => 'Tribunal Regional do Trabalho da 12ª Região'],
            13 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt13/_search', 'nome' => 'Tribunal Regional do Trabalho da 13ª Região'],
            14 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt14/_search', 'nome' => 'Tribunal Regional do Trabalho da 14ª Região'],
            15 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt15/_search', 'nome' => 'Tribunal Regional do Trabalho da 15ª Região'],
            16 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt16/_search', 'nome' => 'Tribunal Regional do Trabalho da 16ª Região'],
            17 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt17/_search', 'nome' => 'Tribunal Regional do Trabalho da 17ª Região'],
            18 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt18/_search', 'nome' => 'Tribunal Regional do Trabalho da 18ª Região'],
            19 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt19/_search', 'nome' => 'Tribunal Regional do Trabalho da 19ª Região'],
            20 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt20/_search', 'nome' => 'Tribunal Regional do Trabalho da 20ª Região'],
            21 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt21/_search', 'nome' => 'Tribunal Regional do Trabalho da 21ª Região'],
            22 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt22/_search', 'nome' => 'Tribunal Regional do Trabalho da 22ª Região'],
            23 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt23/_search', 'nome' => 'Tribunal Regional do Trabalho da 23ª Região'],
            24 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trt24/_search', 'nome' => 'Tribunal Regional do Trabalho da 24ª Região'],
            90 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_csjt/_search', 'nome' => 'Conselho Superior da Justiça do Trabalho'],
        ],
        6 => [
            0  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tse/_search', 'nome' => 'Tribunal Superior Eleitoral'],
            1  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_treac/_search', 'nome' => 'Tribunal Regional Eleitoral do Acre'],
            2  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_treal/_search', 'nome' => 'Tribunal Regional Eleitoral de Alagoas'],
            3  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_treap/_search', 'nome' => 'Tribunal Regional Eleitoral do Amapá'],
            4  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tream/_search', 'nome' => 'Tribunal Regional Eleitoral do Amazonas'],
            5  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_treba/_search', 'nome' => 'Tribunal Regional Eleitoral da Bahia'],
            6  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trece/_search', 'nome' => 'Tribunal Regional Eleitoral do Ceará'],
            7  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tredf/_search', 'nome' => 'Tribunal Regional Eleitoral do Distrito Federal'],
            8  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trees/_search', 'nome' => 'Tribunal Regional Eleitoral do Espírito Santo'],
            9  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trego/_search', 'nome' => 'Tribunal Regional Eleitoral de Goiás'],
            10 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trema/_search', 'nome' => 'Tribunal Regional Eleitoral do Maranhão'],
            11 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tremt/_search', 'nome' => 'Tribunal Regional Eleitoral do Mato Grosso'],
            12 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trems/_search', 'nome' => 'Tribunal Regional Eleitoral do Mato Grosso do Sul'],
            13 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tremg/_search', 'nome' => 'Tribunal Regional Eleitoral de Minas Gerais'],
            14 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trepa/_search', 'nome' => 'Tribunal Regional Eleitoral do Pará'],
            15 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trepb/_search', 'nome' => 'Tribunal Regional Eleitoral da Paraíba'],
            16 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trepr/_search', 'nome' => 'Tribunal Regional Eleitoral do Paraná'],
            17 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trepe/_search', 'nome' => 'Tribunal Regional Eleitoral de Pernambuco'],
            18 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trepi/_search', 'nome' => 'Tribunal Regional Eleitoral do Piauí'],
            19 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trerj/_search', 'nome' => 'Tribunal Regional Eleitoral do Rio de Janeiro'],
            20 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trern/_search', 'nome' => 'Tribunal Regional Eleitoral do Rio Grande do Norte'],
            21 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trers/_search', 'nome' => 'Tribunal Regional Eleitoral do Rio Grande do Sul'],
            22 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trero/_search', 'nome' => 'Tribunal Regional Eleitoral de Rondônia'],
            23 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trerr/_search', 'nome' => 'Tribunal Regional Eleitoral de Roraima'],
            24 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tresc/_search', 'nome' => 'Tribunal Regional Eleitoral de Santa Catarina'],
            25 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_trese/_search', 'nome' => 'Tribunal Regional Eleitoral de Sergipe'],
            26 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tresp/_search', 'nome' => 'Tribunal Regional Eleitoral de São Paulo'],
            27 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_treto/_search', 'nome' => 'Tribunal Regional Eleitoral do Tocantins'],
        ],
        7 => [
            0  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_stm/_search', 'nome' => 'Superior Tribunal Militar'],
            1  => ['link' => '', 'nome' => '1ª Circunscrição Judiciária Militar'],
            2  => ['link' => '', 'nome' => '2ª Circunscrição Judiciária Militar'],
            3  => ['link' => '', 'nome' => '3ª Circunscrição Judiciária Militar'],
            4  => ['link' => '', 'nome' => '4ª Circunscrição Judiciária Militar'],
            5  => ['link' => '', 'nome' => '5ª Circunscrição Judiciária Militar'],
            6  => ['link' => '', 'nome' => '6ª Circunscrição Judiciária Militar'],
            7  => ['link' => '', 'nome' => '7ª Circunscrição Judiciária Militar'],
            8  => ['link' => '', 'nome' => '8ª Circunscrição Judiciária Militar'],
            9  => ['link' => '', 'nome' => '9ª Circunscrição Judiciária Militar'],
            10 => ['link' => '', 'nome' => '10ª Circunscrição Judiciária Militar'],
            11 => ['link' => '', 'nome' => '11ª Circunscrição Judiciária Militar'],
            12 => ['link' => '', 'nome' => '12ª Circunscrição Judiciária Militar'],
        ],
        8 => [
            1  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjac/_search', 'nome' => 'Tribunal de Justiça do Acre'],
            2  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjal/_search', 'nome' => 'Tribunal de Justiça de Alagoas'],
            3  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjap/_search', 'nome' => 'Tribunal de Justiça do Amapá'],
            4  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjam/_search', 'nome' => 'Tribunal de Justiça do Amazonas'],
            5  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjba/_search', 'nome' => 'Tribunal de Justiça da Bahia'],
            6  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjce/_search', 'nome' => 'Tribunal de Justiça do Ceará'],
            7  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjdft/_search', 'nome' => 'Tribunal de Justiça do Distrito Federal e dos Territórios'],
            8  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjes/_search', 'nome' => 'Tribunal de Justiça do Espírito Santo'],
            9  => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjgo/_search', 'nome' => 'Tribunal de Justiça de Goiás'],
            10 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjma/_search', 'nome' => 'Tribunal de Justiça do Maranhão'],
            11 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjmt/_search', 'nome' => 'Tribunal de Justiça do Mato Grosso'],
            12 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjms/_search', 'nome' => 'Tribunal de Justiça do Mato Grosso do Sul'],
            13 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjmg/_search', 'nome' => 'Tribunal de Justiça de Minas Gerais'],
            14 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjpa/_search', 'nome' => 'Tribunal de Justiça do Pará'],
            15 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjpb/_search', 'nome' => 'Tribunal de Justiça da Paraíba'],
            16 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjpr/_search', 'nome' => 'Tribunal de Justiça do Paraná'],
            17 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjpe/_search', 'nome' => 'Tribunal de Justiça de Pernambuco'],
            18 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjpi/_search', 'nome' => 'Tribunal de Justiça do Piauí'],
            19 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjrj/_search', 'nome' => 'Tribunal de Justiça do Rio de Janeiro'],
            20 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjrn/_search', 'nome' => 'Tribunal de Justiça do Rio Grande do Norte'],
            21 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjrs/_search', 'nome' => 'Tribunal de Justiça do Rio Grande do Sul'],
            22 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjro/_search', 'nome' => 'Tribunal de Justiça de Rondônia'],
            23 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjrr/_search', 'nome' => 'Tribunal de Justiça de Roraima'],
            24 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjsc/_search', 'nome' => 'Tribunal de Justiça de Santa Catarina'],
            25 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjse/_search', 'nome' => 'Tribunal de Justiça de Sergipe'],
            26 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjsp/_search', 'nome' => 'Tribunal de Justiça de São Paulo'],
            27 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjto/_search', 'nome' => 'Tribunal de Justiça do Tocantins'],
        ],
        9 => [
            13 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjmmg/_search', 'nome' => 'Tribunal de Justiça Militar de Minas Gerais'],
            21 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjmrs/_search', 'nome' => 'Tribunal de Justiça Militar do Rio Grande do Sul'],
            26 => ['link' => 'https://api-publica.datajud.cnj.jus.br/api_publica_tjmsp/_search', 'nome' => 'Tribunal de Justiça Militar de São Paulo'],
        ],
    ];

    public static function all(): array
    {
        return self::DATA;
    }

    public static function get(int $j, int $tr): ?array
    {
        return self::DATA[$j][$tr] ?? null;
    }
}
