<?php

namespace RGilyov\Trees\Comments;

interface CommentsBuilderInterface
{
    /**
     * @param CommentInterface $root
     * @param array $comments
     */
    public function buildTree(CommentInterface $root, array $comments);

    /**
     * @param CommentInterface $comment
     * @param callable $handle
     * @return void
     */
    public function traverse(CommentInterface $comment, callable $handle);
}
