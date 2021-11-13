<?php

namespace RGilyov\Trees\Interfaces;

interface CommentNode extends Node
{
    /**
     * @param CommentNode $comment
     */
    public function setChild(CommentNode $comment);

    /**
     * @return CommentNode[]
     */
    public function getChildren() : array;

    /**
     * @return int|string
     */
    public function getParentId();
}
