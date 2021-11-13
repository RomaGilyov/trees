<?php

namespace RGilyov\Trees\Interfaces;

interface CommentsTreeBuilder extends TreeBuilder
{
    /**
     * @param CommentNode $root
     * @param array $comments
     */
    public function buildTree(CommentNode $root, array $comments);
}
