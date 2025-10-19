<?php

namespace Shared\Infrastructure;

final readonly class Ulid
{
    public function __construct() {}
    public function generate(): string
    {
        $time = (int) (microtime(true) * 1000);
        $timeEncoded = $this->encodeTime($time, 10);
        $randomEncoded = $this->encodeRandom(16);
        return $timeEncoded . $randomEncoded;
    }

    private function encodeTime(int $time, int $length): string
    {
        $chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
        $encoded = '';
        for ($i = $length - 1; $i >= 0; $i--) {
            $encoded = $chars[$time % 32] . $encoded;
            $time = intdiv($time, 32);
        }
        return $encoded;
    }

    private function encodeRandom(int $bytes): string
    {
        $chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
        $random = random_bytes($bytes);
        $encoded = '';
        for ($i = 0; $i < $bytes; $i++) {
            $encoded .= $chars[ord($random[$i]) & 31];
        }
        return $encoded;
    }
}