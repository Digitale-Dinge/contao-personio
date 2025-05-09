<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\Message;

class PersonioApplicationMessage
{
    public function __construct(
        public readonly array $data,
    ) {
    }
}
