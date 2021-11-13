<?php

namespace RGilyov\Trees\Comments;

use RGilyov\Trees\Exceptions\DuplicateElementsException;
use RGilyov\Trees\Interfaces\CommentNode;
use RGilyov\Trees\Interfaces\CommentsTreeBuilder;

final class CommentsBuilder implements CommentsTreeBuilder
{
    /**
     * @var callable
     */
    private $sortHandler;

    /**
     * @param callable $handler
     * @return $this
     */
    public function sort(callable $handler) : CommentsBuilder
    {
        $this->sortHandler = $handler;

        return $this;
    }

    /**
     * @param CommentNode $root
     * @param CommentNode[] $comments
     * @throws DuplicateElementsException
     */
    public function buildTree(CommentNode $root, array $comments)
    {
        $memo = [$root->getId() => true];

        $this->buildTreeUtil($root, $comments, $memo);
    }

    /**
     * @param CommentNode $root
     * @param CommentNode[] $comments
     * @param array $memo
     * @throws DuplicateElementsException
     */
    private function buildTreeUtil(CommentNode $root, array $comments, array $memo)
    {
        $children = [];

        foreach ($comments as $key => $child) {
            if ($child->getParentId() === $root->getId()) {
                if (isset($memo[$child->getId()])) {
                    throw new DuplicateElementsException("Duplicate elements primary id: {$child->getId()}");
                }

                $memo[$child->getId()] = true;

                $children[] = $child;

                /*
                 * All comments have unique ID thus there is zero sense to
                 * pass the full comment collection further to recursive build.
                 */
                unset($comments[$key]);
            }
        }

        if (is_callable($this->sortHandler) && count($children) > 0) {
            usort($children, $this->sortHandler);
        }

        foreach ($children as $child) {
            $this->buildTreeUtil($child, $comments, $memo);

            $root->setChild($child);
        }
    }
}
