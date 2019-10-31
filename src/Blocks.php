<?php
namespace Nagoyaphp\Dokaku18;

class Blocks
{
    private $table;
    private $delimiter;
    private $maxHexlen;
    private $maxBinlen;

    public function __construct(string $input, string $delimiter = '-')
    {
        $this->delimiter = $delimiter;
        $hexs = explode($this->delimiter, $input);
        $this->maxHexlen = $this->getMaxlen($hexs);
        $bins = array_map(function ($hex) {
            return self::hex2bin($hex);
        }, $hexs);
        $this->maxBinlen = $this->getMaxlen($bins);
        $this->table = array_map(function ($bin) {
            return array_map(function ($s) {
                return intval($s);
            }, str_split(str_pad($bin, $this->maxBinlen, '0', STR_PAD_LEFT)));
        }, $bins);
    }

    public function __toString()
    {
        $result = array_map(function ($c) {
            return str_pad(self::bin2hex(implode($c)), $this->maxHexlen, '0', STR_PAD_LEFT);
        }, $this->table);

        return implode($this->delimiter, $result);
    }

    public function removeFullLine() :void
    {
        $table = $this->table;
        for ($row = 0; $row < $this->maxBinlen; $row++) {
            if ($this->isFullLine($row)) {
                foreach ($table as $key => $value) {
                    unset($value[$row]);
                    $table[$key] = $value;
                }
            }
        }
        $this->table = $table;
    }

    private function getMaxlen(array $input) :int
    {
        return max(array_map(function ($bin) {
            return strlen($bin);
        }, $input));
    }

    private function isFullLine($rowIndex) :bool
    {
        return !in_array(0, $this->getColumn($rowIndex));
    }

    private function getColumn(int $rowIndex) :array
    {
        return array_map(function ($c) use ($rowIndex) {
            return $c[$rowIndex];
        }, $this->table);
    }

    private static function hex2bin(string $val) :string
    {
        return decbin(hexdec($val));
    }

    private static function bin2hex(string $val) :string
    {
        return dechex(bindec($val));
    }
}
