<?php

namespace Idynsys\BillingSdk\Collections;

use Idynsys\BillingSdk\Exceptions\BillingSdkException;
use Iterator;

abstract class Collection implements Iterator
{
    private array $items = [];
    private int $position = 0;
    // Методы интерфейса Iterator
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function valid()
    {
        return isset($this->people[$this->position]);
    }

    public function all(): array
    {
        return $this->items;
    }

    public function addItem(object $item): void
    {
        $this->items[] = $item;
    }

    function checkKeysExists(array $data, ...$requiredKeys): void
    {
        $missingKeys = [];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            $missingKeysString = implode(', ', $missingKeys);
            throw new BillingSdkException("Keys are not found: $missingKeysString", 422);
        }
    }
    public function addItems(array $items, ?string $key = null): Collection
    {
        if ($key) {
            $items = array_key_exists($key, $items) ? $items[$key] : [];
        }

        foreach ($items as $item) {
            $this->addItem($this->itemConvert($item));
        }

        return $this;
    }

    abstract protected function itemConvert($item): object;
}