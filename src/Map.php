<?php

namespace Nagoyaphp\Dokaku18;

class Map
{
    private $values;
    private const HEX_LENGTH = 2;
    private const BIN_LENGTH = 8;
    private const DELIMITER = '-';

    public function __construct(string $input)
    {
        $hexs = explode(self::DELIMITER, $input);
        $this->values = array_map(function ($hex) {
            return hexdec($hex);
        }, $hexs);
    }

    public function __toString()
    {
        return implode(self::DELIMITER, array_map(function ($value) {
            return str_pad(dechex($value), self::HEX_LENGTH, '0', STR_PAD_LEFT);
        }, $this->values));
    }

    public function removeFullLines()
    {
        $row = 0;
        $removeCount = 0;
        while ($row < (self::BIN_LENGTH - $removeCount)) {
            if ($this->isFullLine($row)) {
                foreach ($this->values as $key => $value) {
                    $this->values[$key] =
                        (($value >> ($row + 1)) << $row) + ($value & ((2**$row) - 1));
                }
                $removeCount++;
            } else {
                $row++;
            }
        }
    }

    private function isFullLine($row)
    {
        return empty(array_filter($this->values, function ($value) use ($row) {
            return !(bool)((2**$row) & $value);
        }));
    }
}
