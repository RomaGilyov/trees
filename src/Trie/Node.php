<?php

namespace RGilyov\Trees\Trie;

use RGilyov\Trees\Interfaces\TreeToArray;
use RGilyov\Trees\Interfaces\TrieNode;

final class Node implements TrieNode, TreeToArray
{
    /**
     * @var string|int|float
     */
    private $val;

    /**
     * @var TrieNode[]
     */
    private $children = [];

    /**
     * @param $val
     */
    public function __construct($val = null)
    {
        $this->val = $val;
    }

    /**
     * @return float|int|string
     */
    public function getId()
    {
        return $this->val;
    }

    /**
     * @return TrieNode[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @return Node
     */
    public function reverse() : Node
    {
        $dict = $this->treeToArray();

        $trie = new Node();

        foreach ($dict as $values) {
            $trie->add(array_reverse($values));
        }

        return $trie;
    }

    /**
     * @param array $prefix
     * @return array
     */
    public function prefixSearch(array $prefix) : array
    {
        $results = [];

        $suffix = $this->getSuffix($prefix);

        if (is_null($suffix)) {
            return $results;
        }

        $results = $suffix->treeToArray();

        foreach ($results as $key => $col) {
            $results[$key] = array_merge($prefix, $col);
        }

        return $results;
    }

    /**
     * @param array $values
     */
    public function add(array $values)
    {
        if (empty($values)) {
            return;
        }

        $val = $values[0];

        if (! isset($this->children[$val])) {
            $this->children[$val] = new Node($val);
        }

        $this->children[$val]->add(array_slice($values, 1));
    }

    /**
     * @param array $values
     * @return bool
     */
    public function exists(array $values) : bool
    {
        return $this->existsUtil($values, 0);
    }

    /**
     * @param array $values
     * @param int $index
     * @return bool
     */
    private function existsUtil(array $values, int $index) : bool
    {
        if ($index === count($values)) {
            return true;
        }

        $current = $values[$index];

        $r[] = $current;

        if (isset($this->children[$current])) {
            return $this->children[$current]->existsUtil($values, $index + 1);
        }

        return false;
    }

    /**
     * @param array $values
     * @return $this|null
     */
    public function getSuffix(array $values) : ?Node
    {
        if (empty($values)) {
            return $this;
        }

        $val = $values[0];

        if (isset($this->children[$val])) {
            return $this->children[$val]->getSuffix(array_slice($values, 1));
        }

        return null;
    }

    /**
     * @param array $values
     * @return bool
     */
    public function remove(array $values) : bool
    {
        if (empty($values)) {
            return true;
        }

        $parentSuffix = $this->getSuffix(array_slice($values, 0, count($values) - 1));

        if (is_null($parentSuffix)) {
            return false;
        }

        $suffix = $this->getSuffix($values);

        if (is_null($suffix)) {
            return false;
        }

        if (empty($suffix->children)) {
            unset($parentSuffix->children[$suffix->getId()]);

            return $this->remove(array_slice($values, 0, count($values) - 1));
        }

        return false;
    }

    /**
     * @return array
     */
    public function treeToArray() : array
    {
        $results = [];

        $this->treeToArrayUtil([], $results);

        return $results;
    }

    /**
     * @param array $path
     * @param array $results
     */
    private function treeToArrayUtil(array $path, array &$results)
    {
        /*
                        1
                        /\
                       /  \
                      2    3
                     /\    /\
                    /  \  /  \
                   /    \/	  \
                  4    5  6    7

                1
                1 2
                1 3
                1 2 4
                1 2 5
                1 3 6
                1 3 7
         */
        if (empty($this->children)) {
            $results[] = $path;
        } else {
            foreach ($this->children as $child) {
                $child->treeToArrayUtil(array_merge($path, [$child->getId()]), $results);
            }
        }
    }
}
