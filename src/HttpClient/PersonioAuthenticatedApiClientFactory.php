<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\HttpClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PersonioAuthenticatedApiClientFactory
{
    public function __construct(
        private readonly HttpClientInterface $personioApiClient,
        private readonly string $clientId,
        private readonly string $clientSecret,
    ) {
    }

    public function __invoke(): HttpClientInterface
    {
        $response = $this->personioApiClient
            ->request('POST', 'auth', ['json' => ['client_id' => $this->clientId, 'client_secret' => $this->clientSecret]])
            ->toArray()
        ;

        if (!($response['success'] ?? false)) {
            throw new \RuntimeException('Authentication not successful.');
        }

        if (!$token = ($response['data']['token'] ?? null)) {
            throw new \RuntimeException('No token received.');
        }

        return $this->personioApiClient->withOptions(['auth_bearer' => $token]);
    }
}
