<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\Model;

class JobDescription
{
    public function __construct(
        public string $name,
        public string $value,
    ) {
    }
}
