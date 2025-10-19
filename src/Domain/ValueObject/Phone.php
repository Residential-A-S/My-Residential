<?php

namespace Domain\ValueObject;

use Adapter\Http\Exception\ValidationException;

final class Phone
{
    /**
     * @throws ValidationException
     */
    public function __construct(private string $number)
    {
        $this->number = $this->normalize($number);
        $this->assertValid($this->number);
    }

    private function normalize(string $number): string
    {
        return preg_replace('/\s+/', '', $number);
    }

    /**
     * @throws ValidationException
     */
    private function assertValid(string $number): void
    {
        if (!preg_match('/^\+?[0-9]{6,15}$/', $number)) {
            throw new ValidationException(ValidationException::Phone);
        }
    }

    public function value(): string
    {
        return $this->number;
    }

    public function equals(self $other): bool
    {
        return $this->number === $other->number;
    }
}
