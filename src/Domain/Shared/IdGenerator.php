<?php

namespace Domain\Shared;

interface IdGenerator
{
    public function newId(): Id;
}