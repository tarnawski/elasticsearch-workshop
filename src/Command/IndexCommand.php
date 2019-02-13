<?php

namespace App\Command;

use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class IndexCommand
 * @package App\Command
 */
class IndexCommand extends Command
{
    const INDEX_NAME = 'symfony_demo';
    const TYPE_NAME = 'post';

    protected static $defaultName = 'app:create-index';

    /** @var Client */
    private $client;

    /**
     * IndexCommand constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $params = [
            'index' => self::INDEX_NAME,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    self::TYPE_NAME => [
                        '_source' => [
                            'enabled' => true
                        ],
                        'properties' => [
                            'slug' => [
                                'type' => 'keyword'
                            ],
                            'title' => [
                                'type' => 'text'
                            ],
                            'content' => [
                                'type' => 'text'
                            ],
                            'summary' => [
                                'type' => 'text'
                            ],
                            'author' => [
                                'type' => 'text'
                            ],
                            'date' => [
                                'type' => 'date'
                            ],
                        ]
                    ]
                ]
            ]
        ];

        $this->client->indices()->create($params);

        $output->writeln('Index created!');
    }
}
