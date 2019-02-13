<?php

namespace App\Repository\ElasticSearch;

use App\Entity\Post;
use Elasticsearch\Client;

class PostRepository
{
    const INDEX_NAME = 'symfony_demo';
    const TYPE_NAME = 'post';

    /** @var Client */
    private $client;

    /**
     * PostRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $rawQuery
     * @param int $limit
     * @return array
     */
    public function findBySearchQuery(string $rawQuery, int $limit = Post::NUM_ITEMS): array
    {
        $params = [
            'index' => self::INDEX_NAME,
            'type' => self::TYPE_NAME,
            'size' => $limit,
            'body' => [
                'query' => [
                    'match' => [
                        'title' => $rawQuery
                    ]
                ]
            ]
        ];

        $results = $this->client->search($params);

        if (!isset($results['hits']['hits'])) {
            return [];
        }

        foreach ($results['hits']['hits'] as $result) {
            $posts[] = [
                'slug' => $result['_source']['slug'],
                'title' => $result['_source']['title'],
                'content' => $result['_source']['content'],
                'summary' => $result['_source']['summary'],
                'author' => $result['_source']['author'],
                'date' => $result['_source']['date'],
            ];
        }

        return $posts;
    }
}
