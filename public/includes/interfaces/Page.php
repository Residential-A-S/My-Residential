<?php

namespace interfaces;

interface Page
{
    public function getHead(): string;

    public function getBody(): string;
}
