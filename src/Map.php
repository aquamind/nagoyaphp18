<?php

declare(strict_types=1);

namespace Nagoyaphp\Dokaku18;

class Map
{
    /**
     * @var array
     */
    private $columns;

    public function __construct(string $input)
    {
        foreach (explode('-', $input) as $column) {
            $this->columns[] = array_map(function (string $v) {
                return intval($v);
            }, str_split(sprintf('%08s', base_convert($column, 16, 2))));
        }
    }

    public function getRows() : array
    {
        $rows = [];

        for ($i = 0; $i < 8; $i++) {
            $rows[] = array_map(function ($column) use ($i) {
                return $column[$i];
            }, $this->columns);
        }

        return $rows;
    }

    public function process() : void
    {
        $columns = [];

        $rows = array_values(array_filter($this->getRows(), function ($row) {
            return in_array(0, $row);
        }));

        for ($i = 0; $i < count($this->columns); $i++) {
            $column = array_map(function ($row) use ($i) {
                return $row[$i];
            }, $rows);

            $columns[] = str_split(sprintf('%08s', implode('', $column)));
        }

        $this->columns = $columns;
    }

    public function __toString() : string
    {
        $columns = [];

        foreach ($this->columns as $column) {
            $columns[] = sprintf('%02s', base_convert(implode('', $column), 2, 16));
        }

        return implode('-', $columns);
    }
}
