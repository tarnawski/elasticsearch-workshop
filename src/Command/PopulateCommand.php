<?php

namespace App\Command;

use App\Entity\Post;
use App\Repository\PostRepository;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PopulateCommand
 * @package App\Command
 */
class PopulateCommand extends Command
{
    const INDEX_NAME = 'symfony_demo';
    const TYPE_NAME = 'post';
    const DATE_FORMAT = 'Y-m-d';

    protected static $defaultName = 'app:populate';

    /** @var Client */
    private $client;

    /** @var PostRepository */
    private $postRepository;

    /**
     * PopulateCommand constructor.
     * @param Client $client
     * @param PostRepository $postRepository
     */
    public function __construct(Client $client, PostRepository $postRepository)
    {
        parent::__construct();
        $this->client = $client;
        $this->postRepository = $postRepository;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        /** @var Post $post */
        foreach ($this->postRepository->findAll() as $post) {
            $this->client->index([
                'index' => self::INDEX_NAME,
                'type' => self::TYPE_NAME,
                'id' => $post->getId(),
                'body' => [
                    'slug' => $post->getSlug(),
                    'title' => $post->getTitle(),
                    'content' => $post->getContent(),
                    'summary' => $post->getSummary(),
                    'author' => $post->getAuthor()->getFullName(),
                    'date' => $post->getPublishedAt()->format(self::DATE_FORMAT)
                ]
            ]);
        }

        $output->writeln('Data loaded!');
    }
}
