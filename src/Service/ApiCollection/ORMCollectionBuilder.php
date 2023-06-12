<?php

namespace App\Service\ApiCollection;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Routing\RouterInterface;

class ORMCollectionBuilder
{
    public const DEFAULT_MAX_PER_PAGE = 10;

    private RouterInterface $router;
    private string $routeName;
    private string $maxPerPage;
    private array $routeParams;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string $routeName
     * @param array $routeParams
     * @param int $maxPerPage
     * @return QueryAdapter
     */
    public function configure(
        QueryBuilder $queryBuilder,
        string $routeName,
        array $routeParams = [],
        int $maxPerPage = self::DEFAULT_MAX_PER_PAGE
    ): QueryAdapter {
        $this->routeName = $routeName;
        $this->maxPerPage = $maxPerPage;
        $this->routeParams = $routeParams;

        return new QueryAdapter($queryBuilder);
    }

    public function build(AdapterInterface $adapter, int $page = 1)
    {
        $paginator = (new Pagerfanta($adapter))
            ->setMaxPerPage($this->maxPerPage)
            ->setCurrentPage($page);

        $items = [];
        foreach ($paginator->getCurrentPageResults() as $item) {
            $items[] = $item;
        }

        $collectionDto = (new CollectionDto())
            ->setItems($items);

        $collectionDto->addLink('self', $this->createLink($page));
        $collectionDto->addLink('first', $this->createLink(1));
        $collectionDto->addLink('last', $this->createLink($paginator->getNbPages()));
        if ($paginator->hasNextPage()) {
            $collectionDto->addLink('next', $this->createLink($paginator->getNextPage()));
        }
        if ($paginator->hasPreviousPage()) {
            $collectionDto->addLink('prev', $this->createLink($paginator->getPreviousPage()));
        }

        return $collectionDto;
    }

    /**
     * @param int $page
     * @return string
     */
    private function createLink(int $page): string
    {
        return $this->router->generate($this->routeName, array_merge(
            $this->routeParams,
            array('page' => $page)
        ));
    }
}
