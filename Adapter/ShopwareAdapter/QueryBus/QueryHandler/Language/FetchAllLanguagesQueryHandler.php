<?php

namespace ShopwareAdapter\QueryBus\QueryHandler\Language;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PlentyConnector\Connector\QueryBus\Query\Language\FetchAllLanguagesQuery;
use PlentyConnector\Connector\QueryBus\Query\QueryInterface;
use PlentyConnector\Connector\QueryBus\QueryHandler\QueryHandlerInterface;
use Shopware\Components\Model\ModelRepository;
use Shopware\Models\Shop\Locale;
use ShopwareAdapter\ResponseParser\ResponseParserInterface;
use ShopwareAdapter\ShopwareAdapter;

/**
 * Class FetchAllLanguagesQueryHandler
 */
class FetchAllLanguagesQueryHandler implements QueryHandlerInterface
{
    /**
     * @var ModelRepository
     */
    private $repository;

    /**
     * @var ResponseParserInterface
     */
    private $responseParser;

    /**
     * FetchAllLanguagesQueryHandler constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ResponseParserInterface $responseParser
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ResponseParserInterface $responseParser
    ) {
        $this->repository = $entityManager->getRepository(Locale::class);
        $this->responseParser = $responseParser;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(QueryInterface $event)
    {
        return $event instanceof FetchAllLanguagesQuery &&
            $event->getAdapterName() === ShopwareAdapter::getName();
    }

    /**
     * {@inheritdoc}
     */
    public function handle(QueryInterface $event)
    {
        $query = $this->createLocalesQuery();

        $languages = array_map(function ($language) {
            return $this->responseParser->parse($language);
        }, $query->getArrayResult());

        return array_filter($languages);
    }

    /**
     * @return Query
     */
    private function createLocalesQuery()
    {
        $queryBuilder = $this->repository->createQueryBuilder('locales');
        $queryBuilder->select([
            'locales.id as id',
            'locales.language as name',
            'locales.locale as locale'
        ]);

        $query = $queryBuilder->getQuery();
        $query->execute();

        return $query;
    }
}