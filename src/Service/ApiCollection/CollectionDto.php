<?php

namespace App\Service\ApiCollection;

class CollectionDto
{
    private array $items;
    private array $links;

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return CollectionDto
     */
    public function setItems(array $items): CollectionDto
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    public function addLink(string $key, string $link)
    {
        $this->links[$key] = $link;
    }
}