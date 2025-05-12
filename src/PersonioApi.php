<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio;

use InspiredMinds\ContaoPersonio\Exception\PersonioApiException;
use InspiredMinds\ContaoPersonio\Exception\UnauthorizedException;
use Symfony\Component\Mime\MimeTypesInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PersonioApi
{
    public static array $standardApplicationFields = [
        'first_name',
        'last_name',
        'email',
        'message',
        'cv',
        'cover-letter',
        'employment-reference',
        'certificate',
        'work-sample',
        'other',
    ];

    public static array $systemApplicationAttributes = [
        'birthday',
        'gender',
        'location',
        'phone',
        'available_from',
        'salary_expectations',
    ];

    public function __construct(
        private readonly HttpClientInterface $personioAuthenticatedApiClient,
        private readonly RateLimiterFactory $personioApiRateLimiterFactory,
        private readonly MimeTypesInterface $mimeTypes,
    ) {
    }

    public function postApplicationDocument(string $filepath): array
    {
        $file = new DataPart(fopen($filepath, 'r'), basename($filepath), $this->mimeTypes->guessMimeType($filepath));
        $formData = new FormDataPart(['file' => $file]);
        $options = [
            'body' => $formData->bodyToString(),
            'headers' => $formData->getPreparedHeaders()->toArray(),
        ];

        return $this->request('POST', 'recruiting/applications/documents', $options)->toArray();
    }

    public function postApplication(array $data): void
    {
        $this->request('POST', 'recruiting/applications', ['json' => $data]);
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $this->personioApiRateLimiterFactory->create(md5($url))
            ->reserve()
            ->wait()
        ;

        try {
            $response = $this->personioAuthenticatedApiClient->request($method, $url, $options);
            $response->getHeaders(true);

            return $response;
        } catch (ClientExceptionInterface $e) {
            $response = $e->getResponse();
            $content = $response->getContent(false);

            if (401 === $response->getStatusCode()) {
                throw new UnauthorizedException($content ?: 'Unauthorized - The API token and/or company id was not recognized.', previous: $e);
            }

            try {
                $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

                if ($error = ($data['error']['reason'] ?? null)) {
                    throw new PersonioApiException($error, previous: $e);
                }
            } catch (\JsonException) {
                // noop
            }

            throw new PersonioApiException($content, previous: $e);
        }
    }
}
