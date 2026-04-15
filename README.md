# Brazilian Validators (Core)

Biblioteca PHP para validação de documentos e identificadores brasileiros, com foco em reuso, previsibilidade e baixo acoplamento.

O pacote `casilhero/brazilian-validators` foi projetado para funcionar em qualquer projeto PHP, sem dependencia de framework.

## Sumario

- [Principais objetivos](#principais-objetivos)
- [Requisitos](#requisitos)
- [Instalacao](#instalacao)
- [Validadores disponiveis](#validadores-disponiveis)
- [API publica](#api-publica)
- [Codigos de erro](#codigos-de-erro)
- [Exemplos de uso](#exemplos-de-uso)
- [Compatibilidade](#compatibilidade)
- [Compatibilidade com regras legadas](#compatibilidade-com-regras-legadas)
- [Regras de negocio importantes](#regras-de-negocio-importantes)
- [Qualidade e testes](#qualidade-e-testes)
- [Versionamento](#versionamento)
- [Licenca](#licenca)

## Principais objetivos

- Entregar validacoes brasileiras prontas para producao.
- Evitar copia e cola de regras em cada novo projeto.
- Padronizar retorno de erro para facilitar observabilidade e debug.
- Permitir integracao nativa com Laravel por meio de um pacote bridge separado.

## Requisitos

- PHP `^8.1`

## Instalacao

```bash
composer require casilhero/brazilian-validators
```

## Validadores disponiveis

| Validador           | Classe                 | Descricao resumida                          |
| ------------------- | ---------------------- | ------------------------------------------- |
| CPF                 | `Validators\\Cpf`      | Valida um CPF                               |
| CNPJ                | `Validators\\Cnpj`     | Valida um CNPJ                              |
| CPF/CNPJ            | `Validators\\CpfCnpj`  | Valida um CPF ou CNPJ                       |
| SUFRAMA             | `Validators\\Suframa`  | Valida uma inscrição SUFRAMA                |
| NIS/PIS             | `Validators\\NisPis`   | Valida NIS/PIS com digito verificador       |
| Telefone BR         | `Validators\\Phone`    | Valida DDD + numero local (8 ou 9 digitos)  |
| Telefone BR com DDI | `Validators\\PhoneDdi` | Exige prefixo `55` e validacao nacional     |
| CNH                 | `Validators\\Cnh`      | Valida CNH por digitos verificadores        |
| CNS                 | `Validators\\Cns`      | Valida CNS por checksum e prefixo permitido |

## API publica

Cada validador possui duas portas de entrada:

- `isValid(string $value): bool`
- `validate(string $value): ValidationResult`

### `ValidationResult`

Retorno padrao para cenarios em que somente `true/false` nao e suficiente.

Metodos disponiveis:

- `isValid(): bool`
- `code(): ?string`
- `context(): array`

Tambem existe um agregador para uso centralizado:

- `Casilhero\\BrazilianValidators\\BrazilianValidator`

## Codigos de erro

Codigos padrao fornecidos pelo pacote:

- `invalid_length`
- `invalid_format`
- `invalid_checksum`
- `invalid_region`
- `invalid_prefix`

## Exemplos de uso

### 1) Uso direto de um validador

```php
<?php

use Casilhero\BrazilianValidators\Validators\Cnpj;

$value = '04.252.011/0001-10';

if (! Cnpj::isValid($value)) {
    // tratar erro de validacao
}
```

### 2) Uso com retorno detalhado

```php
<?php

use Casilhero\BrazilianValidators\Validators\Suframa;

$result = Suframa::validate('001234567');

if (! $result->isValid()) {
    echo $result->code(); // invalid_prefix
}
```

### 3) Uso via agregador

```php
<?php

use Casilhero\BrazilianValidators\BrazilianValidator;

$okCpf = BrazilianValidator::cpf('529.982.247-25');
$okPhone = BrazilianValidator::phone('(11) 98765-4321');
```

## Compatibilidade

| Componente     | Versao suportada                   |
| -------------- | ---------------------------------- |
| PHP            | `^8.1`                             |
| Frameworks PHP | Qualquer framework (ou PHP puro)   |
| Laravel        | Suporte via pacote bridge separado |

Bridge oficial Laravel:

- `casilhero/brazilian-validators-laravel`

## Compatibilidade com regras legadas

| Regra legada          | Validador no pacote   | Status                                      |
| --------------------- | --------------------- | ------------------------------------------- |
| `App\\Rules\\Cpf`     | `Validators\\Cpf`     | Comportamento equivalente                   |
| `App\\Rules\\Cnpj`    | `Validators\\Cnpj`    | Comportamento equivalente                   |
| `App\\Rules\\CpfCnpj` | `Validators\\CpfCnpj` | Comportamento equivalente                   |
| `App\\Rules\\Nis`     | `Validators\\NisPis`  | Comportamento equivalente                   |
| `App\\Rules\\Suframa` | `Validators\\Suframa` | Equivalente, com regra explicita de prefixo |

## Regras de negocio importantes

- `Suframa` reprova valores iniciados por `00` antes do calculo de checksum.
- `Phone` valida formato nacional brasileiro (DDD + numero local).
- `PhoneDdi` exige prefixo `55` e aplica validacao brasileira completa.

## Qualidade e testes

Comandos principais:

```bash
composer test
composer test:coverage
```

Politica de qualidade:

- Suite de testes unitarios com Pest.
- Gate minimo de cobertura de linhas: `90%`.

## Versionamento

Este pacote segue Versionamento Semantico (SemVer):

- `MAJOR`: mudancas incompativeis na API publica
- `MINOR`: novas funcionalidades compativeis
- `PATCH`: correcoes sem quebra de contrato

## Licenca

MIT
