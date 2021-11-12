<?php

namespace RGilyov\Trees\Comments;

final class CommentsBuilder implements CommentsBuilderInterface
{
    /**
     * @param CommentInterface $root
     * @param CommentInterface[] $comments
     * @throws DuplicateElementsException
     */
    public function buildTree(CommentInterface $root, array $comments)
    {
        $memo = [$root->getID() => true];

        $this->buildTreeUtil($root, $comments, $memo);
    }

    /**
     * @param CommentInterface $root
     * @param CommentInterface[] $comments
     * @param array $memo
     * @throws DuplicateElementsException
     */
    private function buildTreeUtil(CommentInterface $root, array $comments, array $memo)
    {
        $children = [];

        foreach ($comments as $key => $child) {
            if ($child->getParentID() === $root->getID()) {
                if (isset($memo[$child->getID()])) {
                    throw new DuplicateElementsException("Duplicate elements primary id: {$child->getID()}");
                }

                $memo[$child->getID()] = true;

                $children[] = $child;

                /*
                 * All comments have unique ID thus there is zero sense to
                 * pass the full comment collection further to recursive build.
                 */
                unset($comments[$key]);
            }
        }

        foreach ($children as $child) {
            $this->buildTreeUtil($child, $comments, $memo);

            $root->setChild($child);
        }
    }

    /**
     * @param CommentInterface $comment
     * @param callable $handle
     * @throws DuplicateElementsException
     */
    public function traverse(CommentInterface $comment, callable $handle)
    {
        $memo = [];

        $this->traverseUtil($comment, $handle, $memo);
    }

    /**
     * @param CommentInterface $comment
     * @param callable $handle
     * @param $memo
     * @throws DuplicateElementsException
     */
    private function traverseUtil(CommentInterface $comment, callable $handle, $memo)
    {
        if (isset($memo[$comment->getID()])) {
            throw new DuplicateElementsException("Duplicate elements primary id: {$comment->getID()}");
        }

        if ($handle($comment) === false) {
            return;
        }

        $memo[$comment->getID()] = true;

        foreach ($comment->getChildren() as $child) {
            $this->traverseUtil($child, $handle, $memo);
        }
    }
}