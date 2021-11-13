<?php

namespace RGilyov\Trees\Comments;

use RGilyov\Trees\TreeToArrayInterface;
use RGilyov\Trees\TreeTraversableInterface;

final class Comment implements CommentInterface, TreeToArrayInterface, TreeTraversableInterface
{
    /**
     * @var string
     */
    const ID_KEY = 'id';

    /**
     * @var string
     */
    const PARENT_ID_KEY = 'parent_id';

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $data;

    /**
     * @var CommentInterface[]
     */
    private $children = [];

    /**
     * @param array $data
     * @throws InvalidIdException
     */
    public function __construct(array $data = [])
    {
        if (! isset($data[self::ID_KEY]) || (! is_string($data[self::ID_KEY]) && ! is_numeric($data[self::ID_KEY]))) {
            throw new InvalidIdException("data must contain string or numeric " . self::ID_KEY);
        }

        if (! array_key_exists(self::PARENT_ID_KEY, $data)) {
            throw new InvalidIdException("data must contain " . self::PARENT_ID_KEY);
        }

        $this->data = $data;
    }

    /**
     * @param $offset
     * @return mixed|null
     */
    public function __get($offset)
    {
        return array_key_exists($offset, $this->data) ? $this->data[$offset] : null;
    }

    /**
     * @param $offset
     * @param $value
     */
    public function __set($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /* CommentInterface */

    /**
     * @param CommentInterface $comment
     * @return CommentInterface
     */
    public function setChild(CommentInterface $comment) : CommentInterface
    {
        $this->children[] = $comment;

        return $this;
    }

    /**
     * @return CommentInterface[]
     */
    public function getChildren() : array
    {
        return $this->children;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int|string
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /* util */

    /**
     * @return array
     */
    public function toArray() : array
    {
        $data = $this->data;

        $children = [];

        foreach ($this->children as $child) {
            $children[] = $child->toArray();
        }

        if (count($children) > 0) {
            $data['children'] = $children;
        }

        return $data;
    }

    /**
     * @param callable $handler
     */
    public function traverse(callable $handler)
    {
        if ($handler($this) === false) {
            return;
        }

        foreach ($this->getChildren() as $child) {
            $child->traverse($handler);
        }
    }
}
