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
            if (\in_array($key, PersonioApi::$standardApplicationFields, true)) {
                $application[$key] = $value;
            } else {
                $application['attributes'] ??= [];
                $application['attributes'][] = [
                    'id' => $key,
                    'value' => $value,
                ];
            }
        }

        foreach ($message->files as $key => $value) {
            foreach ((array) $value as $filepath) {
                $file = $this->personioApi->postApplicationDocument($filepath);

                $this->filesystem->remove($filepath);

                $application['files'] ??= [];
                $application['files'][] = [
                    'uuid' => $file['uuid'],
                    'original_filename' => $file['original_filename'],
                    'category' => $key,
                ];
            }
        }

        $this->personioApi->postApplication($application);
    }
}
