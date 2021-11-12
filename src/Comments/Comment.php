<?php

namespace RGilyov\Trees\Comments;

final class Comment implements CommentInterface, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var int|string
     */
    private $id;

    /**
     * @var int|string
     */
    private $parentId;

    /**
     * @var array
     */
    private $data;

    /**
     * @var CommentInterface[]
     */
    private $children = [];

    /**
     * @param $id
     * @param $parentId
     * @param array $data
     */
    public function __construct($id, $parentId, array $data = [])
    {
        $this->id = $id;

        $this->parentId = $parentId;

        $this->data = $data;
    }

    /**
     * @param $offset
     * @return mixed|null
     */
    public function __get($offset)
    {
        return $this->offsetGet($offset);
    }

    /**
     * @param $offset
     * @param $value
     */
    public function __set($offset, $value)
    {
        $this->offsetSet($offset, $value);
    }

    /* CommentInterface */

    /**
     * @param CommentInterface $comment
     * @return CommentInterface
     */
    public function setChild(CommentInterface $comment): CommentInterface
    {
        $this->children[] = $comment;

        return $this;
    }

    /**
     * @return CommentInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return int|string
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * @return int|string
     */
    public function getParentID()
    {
        return $this->parentId;
    }

    /* ArrayAccess */

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return key_exists($offset, $this->data);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        if (! $this->offsetExists($offset)) {
            return null;
        }

        return $this->data[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /* ArrayIterator */

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }
}
