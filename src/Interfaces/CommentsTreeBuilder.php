<?php

namespace RGilyov\Trees\Interfaces;

interface CommentsTreeBuilder
{
    /**
     * @param CommentNode $root
     * @param array $comments
     */
    public function buildTree(CommentNode $root, array $comments);
}
