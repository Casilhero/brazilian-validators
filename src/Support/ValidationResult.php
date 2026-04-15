<?php

declare(strict_types=1);

namespace Casilhero\BrazilianValidators\Support;

final class ValidationResult
{
    /**
     * @param array<string, mixed> $context
     */
    private function __construct(
        private readonly bool $valid,
        private readonly ?string $code = null,
        private readonly array $context = []
    ) {
    }

    public static function valid(): self
    {
        return new self(true);
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function invalid(string $code, array $context = []): self
    {
        return new self(false, $code, $context);
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function code(): ?string
    {
        return $this->code;
    }

    /**
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return $this->context;
    }
}
