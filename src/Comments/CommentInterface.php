<?php

namespace RGilyov\Trees\Comments;

interface CommentInterface
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
    public function getID();

    /**
     * @return int|string
     */
    public function getParentID();
}
