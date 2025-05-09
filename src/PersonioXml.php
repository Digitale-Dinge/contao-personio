<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio;

use InspiredMinds\ContaoPersonio\Model\Jobs;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PersonioXml
{
    public function __construct(
        private readonly HttpClientInterface $personioXmlClient,
        private readonly SerializerInterface $serializer,
        private readonly CacheInterface $cache,
    ) {
    }

    public function getJobs(string $language): Jobs
    {
        $xml = $this->cache->get(
            'personio-xml-'.$language,
            function (ItemInterface $item) use ($language) {
                $item->expiresAfter(\DateInterval::createFromDateString('5 minutes'));

                return $this->personioXmlClient->request('GET', '', ['query' => ['language' => $language]])->getContent();
            },
        );

        return $this->serializer->deserialize($xml, Jobs::class, XmlEncoder::FORMAT);
    }
}
