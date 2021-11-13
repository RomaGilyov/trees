<?php

namespace RGilyov\Trees\Comments;

use RGilyov\Trees\TreeTraversableInterface;

interface CommentInterface extends TreeTraversableInterface
{
    /**
     * @param CommentInterface $comment
     */
    public function setChild(CommentInterface $comment);

    /**
     * @return CommentInterface[]
     */
    public function getChildren() : array;

    /**
     * @return int|string
     */
    public function getId();

    /**
     * @return int|string
     */
    public function getParentId();
}
