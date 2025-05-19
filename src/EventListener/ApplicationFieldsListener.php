<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\StringUtil;

#[AsCallback('tl_content', 'fields.personio_applicationFields.load')]
#[AsCallback('tl_content', 'fields.personio_applicationFields.save')]
class ApplicationFieldsListener
{
    private static $mandatoryFields = [
        'first_name',
        'last_name',
        'email',
    ];

    public function __invoke(mixed $value): string
    {
        $values = StringUtil::deserialize($value, true);

        return serialize(array_unique([...$values, ...self::$mandatoryFields]));
    }
}
