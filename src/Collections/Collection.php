<?php

namespace Idynsys\BillingSdk\Collections;

use Idynsys\BillingSdk\Exceptions\BillingSdkException;
use Iterator;

/**
 * Класс для создания коллекций объектов
 */
abstract class Collection implements Iterator
{
    // Массив элементов коллекции
    private array $items = [];

    // Указатель на текущий элемент итератора
    private int $position = 0;

    // Метод интерфейса Iterator
    public function rewind()
    {
        $this->position = 0;
    }

    // Метод интерфейса Iterator
    public function current()
    {
        return $this->items[$this->position];
    }

    // Метод интерфейса Iterator
    public function key()
    {
        return $this->position;
    }

    // Метод интерфейса Iterator
    public function next()
    {
        $this->position++;
    }

    // Метод интерфейса Iterator
    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * Получить все элементы коллекции
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Добавить объект в коллекцию
     *
     * @param object $item
     * @return void
     */
    public function addItem(object $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Проверка ключей в объекте, для извлечения данных
     *
     * @param array $data
     * @param ...$requiredKeys
     * @return void
     * @throws BillingSdkException
     */
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

    /**
     * Массовая вставка элементов массива в коллекцию
     *
     * @param array $items
     * @param string|null $key
     * @return $this
     */
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

    /**
     * Метод для преобразования элемента перед вставкой в коллекцию
     *
     * @param $item
     * @return object
     */
    abstract protected function itemConvert($item): object;
}