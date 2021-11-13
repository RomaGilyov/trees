<?php

namespace RGilyov\Trees\Genealogy;

use RGilyov\Trees\Exceptions\InfiniteRecursionException;
use RGilyov\Trees\Interfaces\GenealogyNode;
use RGilyov\Trees\Interfaces\GenealogyTreeBuilder;

final class TreeBuilder implements GenealogyTreeBuilder
{
    /**
     * @param GenealogyNode $root
     * @param GenealogyNode[] $ancestors
     * @throws InfiniteRecursionException
     */
    public function buildTree(GenealogyNode $root, array $ancestors)
    {
        /*
           Complexity O(2^n) Because worst case scenario is:

           Each tree level has the same ancestor for all children makes it

           1 = 1
           2 = 3
           3 = 7
           4 = 15
           5 = 31
           ...
           F.e n = 3
                            1
                            /\
                           /  \
                          2    2
                         /\    /\
                        /  \  /  \
                       /    \/	  \
                      3    3  3    3

           In case when all elements are ~unique O(n) (Genealogies usually have ~unique elements)
           F.e n = 7
                             1
                            /\
                           /  \
                          2    3
                         /\    /\
                        /  \  /  \
                       /    \/	  \
                      4    5  6    7
         */

        $memo = [];

        $memo[$root->getId()] = true;

        $mapped = [];

        foreach ($ancestors as $parent) {
            $mapped[$parent->getId()] = $parent;
        }

        $this->buildTreeUtil($root, $mapped, $memo);
    }

    /**
     * @param GenealogyNode $root
     * @param GenealogyNode[] $mapped
     * @param array $memo
     * @throws InfiniteRecursionException
     */
    private function buildTreeUtil(GenealogyNode $root, array $mapped, array $memo)
    {
        /*
           Infinite recursion example:
                         5[3, 4]
                        /\
                       /  \
                      3    4[8, 6]
                     /\    /\
                    /  \  /  \
                   /    \/	  \
                  1    2  8    6[nil, 5] -> Grandfather is a child of the 5th grandchild
                 /\       /\
                /  \     /  \
               7   2    9    2
                       /\
                      /  \
                     x	  2
           Two important things to note:
               1. There are can be the same ancestors for example 3 and 8 has the same ancestor 2
               2. The infinite recursion happens when there is a cyclic reference f.e leaf 6 that points to child 5
                   that would come back to node 5 and go down to 4 and then to 6 and so on...
           5 -> 4 -> 6 -> 5
         */

        if (isset($memo[$root->getFatherId()])) {
            throw new InfiniteRecursionException("f self ref: {$root->getId()} <-> {$root->getFatherId()}");
        }

        if (isset($memo[$root->getMotherId()])) {
            throw new InfiniteRecursionException("m self ref: {$root->getId()} <-> {$root->getMotherId()}");
        }

        if (isset($mapped[$root->getMotherId()])) {
            $mother = $mapped[$root->getMotherId()];

            $motherMemo = $memo;

            $motherMemo[$mother->getId()] = true;

            $root->setMother($mother);

            $this->buildTreeUtil($mother, $mapped, $motherMemo);
        }

        if (isset($mapped[$root->getFatherId()])) {
            $father = $mapped[$root->getFatherId()];

            $fatherMemo = $memo;

            $fatherMemo[$father->getId()] = true;

            $root->setFather($father);

            $this->buildTreeUtil($father, $mapped, $fatherMemo);
        }
    }
}
