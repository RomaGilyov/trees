<?php

namespace RGilyov\Trees\Interfaces;

interface TreeBuilder
{
    /**
     * @param CommentNode $root
     * @param array $comments
     */
    public function buildTree(CommentNode $root, array $comments);
}
