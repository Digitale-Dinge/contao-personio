<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\MessageHandler;

use InspiredMinds\ContaoPersonio\Message\PersonioApplicationMessage;
use InspiredMinds\ContaoPersonio\PersonioApi;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PersonioApplicationHandler
{
    public function __construct(
        private readonly PersonioApi $personioApi,
        private readonly Filesystem $filesystem,
    ) {
    }

    public function __invoke(PersonioApplicationMessage $message): void
    {
        $application = [];

        foreach ($message->data as $key => $value) {
            if (str_starts_with($key, 'file_')) {
                foreach ((array) $value as $filepath) {
                    $category = substr($key, 5);
                    $file = $this->personioApi->postApplicationDocument($filepath);

                    $this->filesystem->remove($filepath);

                    if (!isset($application['files'])) {
                        $application['files'] = [];
                    }

                    $application['files'][] = [
                        'uuid' => $file['uuid'],
                        'original_filename' => $file['original_filename'],
                        'category' => $category,
                    ];
                }
            } else {
                $application[$key] = $value;
            }
        }

        $this->personioApi->postApplication($application);
    }
}
