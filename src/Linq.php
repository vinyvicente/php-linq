<?php
declare(strict_types=1);

namespace Linq;

use function end;
use function reset;
use function array_reverse;
use function array_slice;
use function array_unique;
use function array_filter;
use function count;

class Linq
{
    private iterable $items = [];

    public function select($key = null, $key2 = null)
    {
        if (!$key) {
            return $this->items;
        }

        if (is_callable($key)) {
            $array = $this->items;

            return array_map($key, $array);
        }

        $keys = array_keys($this->items);
        $firstKey = reset($keys);

        if ($key2 && is_object($this->items[$firstKey])) {
            return array_map(static fn ($item) => $item, $this->columnToObject($key, $key2));
        }

        if ($key2) {
            return array_column($this->items, $key2, $key);
        }

        if (is_object($this->items[$firstKey])) {
            return array_map(static fn ($item) => $item, $this->columnToObject($key, $key2));
        }

        return array_column($this->items, $key, null);
    }

    protected function columnToObject($key2, $key): array
    {
        return array_column(array_map(static fn ($item) => (array) $item, $this->items), $key2, $key);
    }

    public function from(iterable $items): Linq
    {
        $this->items = $items;

        return $this;
    }

    public function first()
    {
        return reset($this->items);
    }

    public function last()
    {
        return end($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function reverse(): Linq
    {
        $this->items = array_reverse($this->items, true);

        return $this;
    }

    public function take(int $offset, ?int $length = null)
    {
        return array_slice($this->items, $offset, $length);
    }

    public function distinct(): Linq
    {
        $this->items = array_unique($this->items, SORT_REGULAR);

        return $this;
    }

    public function where(callable $condition): Linq
    {
        $this->items = array_filter($this->items, $condition);

        return $this;
    }
}
