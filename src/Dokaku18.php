<?php

declare(strict_types=1);

namespace Nagoyaphp\Dokaku18;

class Dokaku18
{
    public function run(string $input) : string
    {
        $blocks = new Blocks($input);
        $blocks->removeFullLine();
        return strval($blocks);
    }
}
