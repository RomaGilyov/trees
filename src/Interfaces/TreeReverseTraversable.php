<?php

namespace RGilyov\Trees\Interfaces;

interface TreeReverseTraversable
{
    /**
     * From leaf to root
     *
     * @param callable $handler
     * @return void
     */
    public function reverseTraverse(callable $handler);
}
