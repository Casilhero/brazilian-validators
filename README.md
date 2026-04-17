# casilhero/brazilian-validators

Biblioteca PHP para validação de documentos e identificadores brasileiros. Sem dependência de framework — funciona em qualquer projeto PHP puro, Laravel, Symfony, etc.

## Requisitos

- PHP `^8.1`

## Instalação

```bash
composer require casilhero/brazilian-validators
```

## Validadores disponíveis

| Validador           | Classe                         | Descrição                                                         |
| ------------------- | ------------------------------ | ----------------------------------------------------------------- |
| CPF                 | `Validators\Cpf`               | Valida CPF com dígito verificador                                 |
| CNPJ                | `Validators\Cnpj`              | Valida CNPJ com dígito verificador                                |
| CPF ou CNPJ         | `Validators\CpfCnpj`           | Detecta o tipo e valida automaticamente                           |
| SUFRAMA             | `Validators\Suframa`           | Valida inscrição SUFRAMA                                          |
| NIS/PIS             | `Validators\NisPis`            | Valida NIS/PIS com dígito verificador                             |
| Telefone BR         | `Validators\Phone`             | Valida DDD + número local (8 ou 9 dígitos)                        |
| Telefone BR com DDI | `Validators\PhoneDdi`          | Exige prefixo `55` + validação nacional                           |
| CNH                 | `Validators\Cnh`               | Valida CNH pelos dois dígitos verificadores                       |
| CNS                 | `Validators\Cns`               | Valida Cartão Nacional de Saúde                                   |
| RENAVAM             | `Validators\Renavam`           | Valida RENAVAM com dígito verificador                             |
| Chassi              | `Validators\Chassi`            | Valida número de chassi (padrão VIN)                              |
| Título de Eleitor   | `Validators\TituloEleitor`     | Valida título de eleitor (8, 9 ou 10 dígitos)                     |
| Inscrição Estadual  | `Validators\InscricaoEstadual` | Valida IE de todos os 26 estados + DF (ver tabela abaixo)         |
| Certidão            | `Validators\Certidao`          | Valida número de certidão do Registro Civil (CNJ, 32 dígitos)     |
| Passaporte          | `Validators\Passaporte`        | Valida passaporte brasileiro (2 letras + 6 dígitos)               |
| CAEPF               | `Validators\Caepf`             | Valida CAEPF (Cadastro de Atividade Econômica da Pessoa Física)   |
| Processo Judicial   | `Validators\ProcessoJudicial`  | Valida número CNJ (Res. 65/2008): `NNNNNNN-DD.AAAA.J.TR.OOOO`     |
| Boleto              | `Validators\Boleto`            | Valida boleto bancário (47 dígitos) e de arrecadação (48 dígitos) |

### Inscrição Estadual — estados suportados

Os algoritmos foram verificados individualmente contra os roteiros oficiais de crítica publicados pelo [SINTEGRA](https://www.sintegra.gov.br/insc_est.html).

| UF  | Tamanho                                 | Observações                                                                            |
| --- | --------------------------------------- | -------------------------------------------------------------------------------------- |
| AC  | 13 dígitos                              | Prefixo `01`; 2 DVs (mod 11)                                                           |
| AL  | 9 dígitos                               | Prefixo `24`; 1 DV (mod 11)                                                            |
| AM  | 9 dígitos                               | 1 DV (mod 11)                                                                          |
| AP  | 9 dígitos                               | Prefixo `03`; 1 DV (mod 11); regras especiais para faixas `03000840`–`03017600`        |
| BA  | 8 ou 9 dígitos                          | 2 DVs; módulo 10 ou 11 conforme 1º dígito (8d) ou 2º dígito (9d)                       |
| CE  | 9 dígitos                               | 1 DV (mod 11)                                                                          |
| DF  | 13 dígitos                              | Prefixo `07`; 2 DVs (mod 11, ciclo 2–9)                                                |
| ES  | 9 dígitos                               | 1 DV (mod 11)                                                                          |
| GO  | 9 dígitos                               | Prefixos `10`, `11` ou `20`–`29`; 1 DV (mod 11)                                        |
| MA  | 9 dígitos                               | Prefixo `12`; 1 DV (mod 11)                                                            |
| MG  | 13 dígitos                              | 2 DVs; DV1 com soma de algarismos dos produtos (pesos 1×2); DV2 (mod 11, ciclo 3–11)   |
| MS  | 9 dígitos                               | Prefixo `28` ou `50`; 1 DV (mod 11)                                                    |
| MT  | 11 dígitos                              | 1 DV (mod 11, ciclo 3–9)                                                               |
| PA  | 9 dígitos                               | Prefixos `15`, `75`–`79`; 1 DV (mod 11)                                                |
| PB  | 9 dígitos                               | 1 DV (mod 11)                                                                          |
| PE  | 9 dígitos (novo)                        | 2 DVs (mod 11)                                                                         |
| PE  | 14 dígitos (antigo CACEPE)              | 1 DV (mod 11, ciclo 1–9); >9 → −10                                                     |
| PI  | 9 dígitos                               | 1 DV (mod 11)                                                                          |
| PR  | 10 dígitos                              | 2 DVs (mod 11, pesos ciclo 2–7)                                                        |
| RJ  | 8 dígitos                               | 1 DV (mod 11, pesos 2,7,6,5,4,3,2)                                                     |
| RN  | 9 ou 10 dígitos                         | Prefixo `20`; 1 DV via `(soma×10) % 11`                                                |
| RO  | 9 dígitos (antigo) ou 14 dígitos (novo) | 1 DV (mod 11); >10 → −10                                                               |
| RR  | 9 dígitos                               | Prefixo `24`; 1 DV (soma × posições 1–8) mod 9                                         |
| RS  | 10 dígitos                              | 1 DV (mod 11, ciclo 2–9)                                                               |
| SC  | 9 dígitos                               | 1 DV (mod 11); resto < 2 → 0                                                           |
| SE  | 9 dígitos                               | 1 DV (mod 11); ≥ 10 → 0                                                                |
| SP  | 12 dígitos (padrão)                     | 2 DVs; pesos 1,3,4,5,6,7,8,10 e 3,2,10,9,8,7,6,5,4,3,2; resto % 10                     |
| SP  | 13 caracteres `P…` (produtor rural)     | 1 DV; mesmos pesos do DV1 padrão; resto % 10                                           |
| TO  | 11 dígitos                              | Posições 3–4 indicam tipo (`01`/`02`/`03`/`99`) e não entram no cálculo; 1 DV (mod 11) |

> Referência técnica: [SINTEGRA — Roteiros de Crítica da Inscrição Estadual](https://www.sintegra.gov.br/insc_est.html)

## API

Todos os validadores expõem os seguintes métodos estáticos:

```php
// Validação
Cnpj::isValid(string $value): bool
Cnpj::validate(string $value): ValidationResult

// Geração de dados de teste (retorna string sem máscara)
Cnpj::generate(): string

// Aplicação de máscara ao valor limpo
Cnpj::mask(string $value): string
```

> **Nota:** `InscricaoEstadual` não implementa `generate()` nem `mask()` por depender de algoritmos específicos por UF. Os métodos `Certidao::parse()` e `Boleto::parse()` estão disponíveis para extrair os campos estruturados do número (ver exemplos).

### `ValidationResult`

Retornado por `validate()`. Útil quando você precisa do motivo da reprovação.

```php
$result = Suframa::validate('001234567');

$result->isValid(); // bool
$result->code();    // string|null — código de erro (veja abaixo)
$result->context(); // array — dados extras do erro
```

### Códigos de erro

| Código             | Significado                  |
| ------------------ | ---------------------------- |
| `invalid_length`   | Tamanho incorreto            |
| `invalid_format`   | Formato não reconhecido      |
| `invalid_checksum` | Dígito verificador inválido  |
| `invalid_region`   | Região/DDD/tribunal inválido |
| `invalid_prefix`   | Prefixo não permitido        |

### `BrazilianValidator` — façade estática central

Agrega todos os validadores em uma única classe para uso centralizado:

```php
use Casilhero\BrazilianValidators\BrazilianValidator;

// Retorno bool
BrazilianValidator::cpf('529.982.247-25');
BrazilianValidator::cnpj('04.252.011/0001-10');
BrazilianValidator::cpfCnpj('04.252.011/0001-10');
BrazilianValidator::suframa('010234567');
BrazilianValidator::nisPis('12345678919');
BrazilianValidator::phone('(11) 98765-4321');
BrazilianValidator::phoneDdi('5511987654321');
BrazilianValidator::cnh('12345678900');
BrazilianValidator::cns('700009600073');
BrazilianValidator::renavam('77077411168');
BrazilianValidator::chassi('9BWZZZ377VT004251');
BrazilianValidator::tituloEleitor('123456789012');
BrazilianValidator::inscricaoEstadual('110042490114', 'SP');
BrazilianValidator::certidao('10514 01 55 2024 1 00001 092 0000250-28');
BrazilianValidator::passaporte('AB123456');
BrazilianValidator::caepf('132.574.492/00-1');
BrazilianValidator::processoJudicial('0000001-41.2024.8.01.0001');
BrazilianValidator::boleto('34191.79001 01043.510047 91020.150008 2 85480000000000');

// Retorno ValidationResult (sufixo Result)
$result = BrazilianValidator::cpfResult('11111111111');
if (! $result->isValid()) {
    echo $result->code(); // invalid_format
}

// Geração de dados de teste
$cpf    = BrazilianValidator::cpfGenerate();                // ex: '52998224725'
$cnpj   = BrazilianValidator::cnpjGenerate();               // ex: '04252011000110'
$phone  = BrazilianValidator::phoneGenerate();              // ex: '11987654321'
$cnh    = BrazilianValidator::cnhGenerate();                // ex: '12345678900'
$proc   = BrazilianValidator::processoJudicialGenerate();   // ex: '00000014120248010001'
$boleto = BrazilianValidator::boletoGenerate();             // ex: '34191790010104351004791020150008285480000000000'
// demais: cpfCnpjGenerate, suframaGenerate, nisPisGenerate, phoneDdiGenerate,
//         cnsGenerate, renavamGenerate, chassiGenerate, tituloEleitorGenerate,
//         certidaoGenerate, passaporteGenerate, caepfGenerate

// Aplicação de máscara
$maskedCpf    = BrazilianValidator::cpfMask('52998224725');    // '529.982.247-25'
$maskedCnpj   = BrazilianValidator::cnpjMask('04252011000110'); // '04.252.011/0001-10'
$maskedPhone  = BrazilianValidator::phoneMask('11987654321');  // '(11) 98765-4321'
$maskedBoleto = BrazilianValidator::boletoMask('34191790010104351004791020150008285480000000000');
//   '34191.79001 01043.510047 91020.150008 2 85480000000000'
$maskedProc   = BrazilianValidator::processoJudicialMask('00000014120248010001');
//   '0000001-41.2024.8.01.0001'
// demais: cpfCnpjMask, suframaMask, nisPisMask, phoneDdiMask, cnhMask,
//         renavamMask, chassiMask, tituloEleitorMask, certidaoMask,
//         passaporteMask, caepfMask

// Parse (extrai campos estruturados)
$certidaoInfo = BrazilianValidator::certidaoParse('10514 01 55 2024 1 00001 092 0000250-28');
$certidaoInfo?->codigoServentia;  // '10514'
$certidaoInfo?->ano;              // 2024
$certidaoInfo?->descricaoLivro(); // 'Livro A (Nascimento)'

$boletoInfo = BrazilianValidator::boletoParse('00190000090114971860168524522114675860000102656');
$boletoInfo?->type;            // 'bancario'
$boletoInfo?->bankCode;        // '001'
$boletoInfo?->amount;          // 102656
$boletoInfo?->amountInReals(); // 1026.56
$boletoInfo?->expirationDate;  // \DateTimeImmutable
```

## Exemplos

### Validação simples

```php
use Casilhero\BrazilianValidators\Validators\Cnpj;

if (! Cnpj::isValid($request->cnpj)) {
    throw new \InvalidArgumentException('CNPJ inválido.');
}
```

### Validação com retorno detalhado

```php
use Casilhero\BrazilianValidators\Validators\Cns;

$result = Cns::validate($input);

if (! $result->isValid()) {
    logger()->warning('CNS inválido', ['code' => $result->code()]);
}
```

### Geração de dados de teste

```php
use Casilhero\BrazilianValidators\Validators\Cpf;
use Casilhero\BrazilianValidators\Validators\ProcessoJudicial;

$cpf  = Cpf::generate();               // '52998224725' (sem máscara)
$cpfF = Cpf::mask(Cpf::generate());    // '529.982.247-25' (com máscara)
$proc = ProcessoJudicial::generate();  // '00000014120248010001'
$procF = ProcessoJudicial::mask($proc); // '0000001-41.2024.8.01.0001'
```

### Parse de certidão

```php
use Casilhero\BrazilianValidators\Validators\Certidao;

$info = Certidao::parse('10514 01 55 2024 1 00001 092 0000250-28');
$info?->codigoServentia;  // '10514'
$info?->codigoAcervo;     // '01'
$info?->ano;              // 2024
$info?->tipoLivro;        // 1
$info?->descricaoLivro(); // 'Livro A (Nascimento)'
$info?->toArray();        // array com todos os campos
```

### Parse de boleto

```php
use Casilhero\BrazilianValidators\Validators\Boleto;

$info = Boleto::parse('00190000090114971860168524522114675860000102656');
$info?->type;            // 'bancario'
$info?->bankCode;        // '001'
$info?->amount;          // 102656
$info?->amountInReals(); // 1026.56
$info?->expirationDate;  // \DateTimeImmutable('2018-07-15')
```

## Compatibilidade

| Componente | Versão suportada                                                                                        |
| ---------- | ------------------------------------------------------------------------------------------------------- |
| PHP        | `^8.1` (inclui 8.2, 8.3, 8.4 e 8.5)                                                                     |
| Framework  | Qualquer (ou PHP puro)                                                                                  |
| Laravel    | via [casilhero/brazilian-validators-laravel](https://github.com/Casilhero/brazilian-validators-laravel) |

## Testes

```bash
composer test            # executa a suíte Pest
composer test:coverage   # executa com relatório de cobertura (mínimo 90%)
composer format          # aplica Laravel Pint
composer format:check    # verifica formatação sem alterar arquivos
```

## Versionamento

Segue [SemVer](https://semver.org/lang/pt-BR/):

- `MAJOR` — quebra de compatibilidade na API pública
- `MINOR` — novas funcionalidades compatíveis com versões anteriores
- `PATCH` — correções sem quebra de contrato

## Licença

MIT
