<?php

namespace Nagoyaphp\Dokaku18;

class Map
{
    private $delimiter;
    private $values;
    private const HEX_LENGTH = 2;
    private const BIN_LENGTH = 8;

    public function __construct(string $input, string $delimiter = '-')
    {
        $this->delimiter = $delimiter;
        $hexs = explode($this->delimiter, $input);
        $this->values = array_map(function ($hex) {
            return hexdec($hex);
        }, $hexs);
    }

    public function __toString()
    {
        return implode($this->delimiter, array_map(function ($value) {
            return str_pad(dechex($value), self::HEX_LENGTH, '0', STR_PAD_LEFT);
        }, $this->values));
    }

    public function removeFullLines($startPlace = 0)
    {
        for ($place = $startPlace; $place < self::BIN_LENGTH; $place++) {
            $placeValue = 2**$place;
            if (empty(array_filter($this->values, function ($value) use ($placeValue) {
                return !(bool)($placeValue & $value);
            }))) {
                foreach ($this->values as $key => $value) {
                    $this->values[$key] = ($value % $placeValue) +
                        (floor($value / ($placeValue * 2)) * $placeValue);
                }
                $this->removeFullLines($place);
                return;
            }
        }
    }
}
