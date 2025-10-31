<?php

namespace Domain\ValueObject;

use Domain\Exception\PhoneException;

/**
 *
 */
final class Phone
{
    /**
     * @throws PhoneException
     */
    public function __construct(public string $value)
    {
        $this->value = $this->normalize($value);
        $this->assertValid($this->value);
    }

    /**
     * @param string $number
     *
     * @return string
     */
    private function normalize(string $number): string
    {
        return preg_replace('/\s+/', '', $number);
    }

    /**
     * @throws PhoneException
     */
    private function assertValid(string $number): void
    {
        if (!preg_match('/^\+?[0-9]{6,15}$/', $number)) {
            throw new PhoneException(PhoneException::PHONE_INVALID);
        }
    }

    /**
     * @param Phone $other
     *
     * @return bool
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
