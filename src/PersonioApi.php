<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio;

use InspiredMinds\ContaoPersonio\Exception\PersonioApiException;
use InspiredMinds\ContaoPersonio\Exception\UnauthorizedException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PersonioApi
{
    public function __construct(
        private readonly HttpClientInterface $personioAuthenticatedApiClient,
        private readonly RateLimiterFactory $personioApiRateLimiterFactory,
    ) {
    }

    public function postApplicationDocument(string $filepath): array
    {
        return $this->request('POST', 'recruiting/applications/documents', ['body' => ['file' => fopen($filepath, 'r')]])->toArray();
    }

    public function postApplication(array $data): void
    {
        $this->request('POST', 'recruiting/applications', ['json' => $data]);
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $limiter = $this->personioApiRateLimiterFactory->create(md5($url));
        $limiter->reserve()->wait();

        try {
            $response = $this->personioAuthenticatedApiClient->request($method, $url, $options);
            $response->getHeaders(true);

            return $response;
        } catch (ClientExceptionInterface $e) {
            $response = $e->getResponse();
            $content = $response->getContent(false);

            if (401 === $response->getStatusCode()) {
                throw new UnauthorizedException($content ?: 'Unauthorized - The API token and/or company id was not recognized.');
            }

            throw new PersonioApiException($content);
        }
    }
}
