# Brazilian Validators (Core)

Biblioteca PHP para validação de documentos e identificadores brasileiros, com foco em reuso, previsibilidade e baixo acoplamento.

O pacote `casilhero/brazilian-validators` foi projetado para funcionar em qualquer projeto PHP, sem dependência de framework.

## Sumário

- [Principais objetivos](#principais-objetivos)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Validadores disponíveis](#validadores-disponíveis)
- [API pública](#api-pública)
- [Códigos de erro](#códigos-de-erro)
- [Exemplos de uso](#exemplos-de-uso)
- [Compatibilidade](#compatibilidade)
- [Compatibilidade com regras legadas](#compatibilidade-com-regras-legadas)
- [Regras de negócio importantes](#regras-de-negócio-importantes)
- [Qualidade e testes](#qualidade-e-testes)
- [Versionamento](#versionamento)
- [Licença](#licença)

## Principais objetivos

- Entregar validações brasileiras prontas para produção.
- Evitar cópia e cola de regras em cada novo projeto.
- Padronizar retorno de erro para facilitar observabilidade e debug.
- Permitir integração nativa com Laravel por meio de um pacote bridge separado.

## Requisitos

- PHP `^8.1`

## Instalação

```bash
composer require casilhero/brazilian-validators
```

## Validadores disponíveis

| Validador           | Classe                 | Descrição resumida                           |
| ------------------- | ---------------------- | -------------------------------------------- |
| CPF                 | `Validators\\Cpf`      | Valida um CPF                                |
| CNPJ                | `Validators\\Cnpj`     | Valida um CNPJ                               |
| CPF/CNPJ            | `Validators\\CpfCnpj`  | Valida um CPF ou CNPJ                        |
| SUFRAMA             | `Validators\\Suframa`  | Valida uma inscrição SUFRAMA                 |
| NIS/PIS             | `Validators\\NisPis`   | Valida NIS/PIS com dígito verificador        |
| Telefone BR         | `Validators\\Phone`    | Valida DDD + número local (8 ou 9 dígitos)   |
| Telefone BR com DDI | `Validators\\PhoneDdi` | Exige prefixo `55` e validação nacional      |
| CNH                 | `Validators\\Cnh`      | Valida CNH por dígitos verificadores         |
| CNS                 | `Validators\\Cns`      | Valida CNS por checksum e prefixo permitido  |

## API pública

Cada validador possui duas portas de entrada:

- `isValid(string $value): bool`
- `validate(string $value): ValidationResult`

### `ValidationResult`

Retorno padrão para cenários em que somente `true/false` não é suficiente.

Métodos disponíveis:

- `isValid(): bool`
- `code(): ?string`
- `context(): array`

Também existe um agregador para uso centralizado:

- `Casilhero\\BrazilianValidators\\BrazilianValidator`

## Códigos de erro

Códigos padrão fornecidos pelo pacote:

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
    // tratar erro de validação
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

| Componente     | Versão suportada                    |
| -------------- | ----------------------------------- |
| PHP            | `^8.1` (inclui 8.5)                 |
| Frameworks PHP | Qualquer framework (ou PHP puro)    |
| Laravel        | Suporte via pacote bridge separado  |

Bridge oficial Laravel:

- `casilhero/brazilian-validators-laravel`

## Compatibilidade com regras legadas

| Regra legada          | Validador no pacote   | Status                                         |
| --------------------- | --------------------- | ---------------------------------------------- |
| `App\\Rules\\Cpf`     | `Validators\\Cpf`     | Comportamento equivalente                      |
| `App\\Rules\\Cnpj`    | `Validators\\Cnpj`    | Comportamento equivalente                      |
| `App\\Rules\\CpfCnpj` | `Validators\\CpfCnpj` | Comportamento equivalente                      |
| `App\\Rules\\Nis`     | `Validators\\NisPis`  | Comportamento equivalente                      |
| `App\\Rules\\Suframa` | `Validators\\Suframa` | Equivalente, com regra explícita de prefixo    |

## Regras de negócio importantes

- `Suframa` reprova valores iniciados por `00` antes do cálculo de checksum.
- `Phone` valida formato nacional brasileiro (DDD + número local).
- `PhoneDdi` exige prefixo `55` e aplica validação brasileira completa.

## Qualidade e testes

Comandos principais:

```bash
composer test
composer test:coverage
```

Política de qualidade:

- Suíte de testes unitários com Pest.
- Gate mínimo de cobertura de linhas: `90%`.

## Versionamento

Este pacote segue Versionamento Semântico (SemVer):

- `MAJOR`: mudanças incompatíveis na API pública
- `MINOR`: novas funcionalidades compatíveis
- `PATCH`: correções sem quebra de contrato

## Licença

MIT
