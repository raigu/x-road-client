<?php

namespace Raigu\XRoad;

interface Requestable
{
    public function request(string $request): string;
}
