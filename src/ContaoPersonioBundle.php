<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoPersonioBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
