<?php

namespace RGilyov\Trees\Interfaces;

interface TreeTraversable
{
    /**
     * @param callable $handler
     * @return void
     */
    public function traverse(callable $handler);
}
