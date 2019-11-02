<?php

declare(strict_types=1);

namespace Nagoyaphp\Dokaku18;

class Dokaku18
{
    public function run(string $input) : string
    {
        $map = new Map($input);
        $map->removeFullLines();

        return strval($map);
    }
}
