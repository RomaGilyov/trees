<?php

namespace RGilyov\Trees\Genealogy;

use RGilyov\Trees\Exceptions\InvalidIdException;
use RGilyov\Trees\Interfaces\GenealogyNode;
use RGilyov\Trees\Interfaces\TreeReverseTraversable;
use RGilyov\Trees\Interfaces\TreeToArray;
use RGilyov\Trees\Interfaces\TreeTraversable;

final class Node implements GenealogyNode, TreeToArray, TreeTraversable, TreeReverseTraversable
{
    /**
     * @var string
     */
    const ID_KEY = 'id';

    /**
     * @var string
     */
    const MOTHER_ID_KEY = 'mother_id';

    /**
     * @var string
     */
    const FATHER_ID_KEY = 'father_id';

    /**
     * @var GenealogyNode
     */
    private $mother;

    /**
     * @var GenealogyNode
     */
    private $father;

    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     * @throws InvalidIdException
     */
    public function __construct(array $data = [])
    {
        if (! isset($data[self::ID_KEY]) || (! is_string($data[self::ID_KEY]) && ! is_numeric($data[self::ID_KEY]))) {
            throw new InvalidIdException("data must contain string or numeric " . self::ID_KEY);
        }

        if (! array_key_exists(self::MOTHER_ID_KEY, $data)) {
            throw new InvalidIdException("data must contain " . self::MOTHER_ID_KEY);
        }

        if (! array_key_exists(self::FATHER_ID_KEY, $data)) {
            throw new InvalidIdException("data must contain " . self::FATHER_ID_KEY);
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
    public function getMotherId()
    {
        return $this->mother_id;
    }

    /**
     * @return int|string
     */
    public function getFatherId()
    {
        return $this->father_id;
    }

    /**
     * @param GenealogyNode $node
     */
    public function setMother(GenealogyNode $node)
    {
        $this->mother = $node;
    }

    /**
     * @param GenealogyNode $node
     */
    public function setFather(GenealogyNode $node)
    {
        $this->father = $node;
    }

    /**
     * @return GenealogyNode
     */
    public function getMother() : ?GenealogyNode
    {
        return $this->mother;
    }

    /**
     * @return GenealogyNode
     */
    public function getFather() : ?GenealogyNode
    {
        return $this->father;
    }

    /* util */

    /**
     * @return array
     */
    public function treeToArray() : array
    {
        $data = $this->data;

        $mother = $this->getMother();
        $father = $this->getFather();

        if (! is_null($mother)) {
            $data['mother'] = $mother->treeToArray();
        }

        if (! is_null($father)) {
            $data['father'] = $father->treeToArray();
        }

        return $data;
    }

    /**
     * @param callable $handler
     */
    public function traverse(callable $handler)
    {
        $this->traverseUtil($handler, 0);
    }

    /**
     * @param callable $handler
     * @param int $level
     */
    private function traverseUtil(callable $handler, int $level)
    {
        if ($handler($this, $level) === false) {
            return;
        }

        $level++;

        $father = $this->getFather();

        if ($father) {
            $father->traverseUtil($handler, $level);
        }

        $mother = $this->getMother();

        if ($mother) {
            $mother->traverseUtil($handler, $level);
        }
    }

    /**
     * @param callable $handler
     */
    public function reverseTraverse(callable $handler)
    {
        /*
            Example of traversing a genealogy up:

            F.e we have some entities, and we need to calculate how a certain trait
            has transferred from ancestors to a certain child. F.e we have a trait X, and we
            need to calculate what percentage of that trait has passed to a child and so on...

            For the sake of simplicity assume that X has 100% passing further:
            1. If ancestor A has the trait and B does not: 100% + 0%/2 = 50% passed further
            2. If ancestor A has the trait and B has the trait: 100% + 100%/2 = 100% passed further
            3. If ancestor A does not have the trait and B does not have the trait: 0% + 0%/2 = 0% passed further
            4. Assume a missing ancestor has 0% as X

            In this case the genealogy must have this values:

                         5 X transfer is 56.25%
                        /\
                       /  \
                      3    4 [3]75% + [4]37.5%/2 ^
                     /\    /\
                    /  \  /  \
                   /    \/	  \
                  1    2  8    6 [8]75% + [6]0%/2 ^
                 /\       /\
                /  \     /  \
               7   2    9    2  [9]50% + [2]100%/2 ^
                        \
                         \
                          2 [Gen X] -> [?] [2]100%/2

            Complexity O(2n)
         */

        $levels = [];

        $i = 0;

        $this->traverse(function (GenealogyNode $node, $level) use (&$levels, &$i) {
            if (! isset($levels[$level])) {
                $levels[$level] = [];
            }

            $levels[$level][] = &$node;

            if ($i < $level) {
                $i = $level;
            }
        });

        while ($i >= 0) {
            foreach ($levels[$i] as &$node) {
                if ($handler($node) === false) {
                    return;
                }
            }

            $i--;
        }
    }
}
