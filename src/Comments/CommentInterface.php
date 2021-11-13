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
    public function getId();

    /**
     * @return int|string
     */
    public function getParentId();
}
