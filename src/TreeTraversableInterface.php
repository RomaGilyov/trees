<?php

namespace RGilyov\Trees;

interface TreeTraversableInterface
{
    /**
     * @param callable $handler
     * @return void
     */
    public function traverse(callable $handler);
}
