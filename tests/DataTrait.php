<?php

namespace RGilyov\Trees\Test;

use RGilyov\Trees\Comments\Node as CommentNode;
use RGilyov\Trees\Genealogy\Node as GenealogyNode;
use RGilyov\Trees\Exceptions\InvalidIdException;

trait DataTrait
{
    /**
     * 1 ->
     *     2
     *     3 ->
     *         4
     *         5 ->
     *             6
     *             7
     *             8
     *
     * @return CommentNode[]
     * @throws InvalidIdException
     */
    private function commentsTestData()
    {
        return [
            new CommentNode(['id' => 1, 'parent_id' => null, 'value' => 'Root comment']),
            new CommentNode(['id' => 2, 'parent_id' => 1, 'value' => '1st level comment']),
            new CommentNode(['id' => 3, 'parent_id' => 1, 'value' => '1st level comment']),
            new CommentNode(['id' => 4, 'parent_id' => 3, 'value' => '2nd level comment']),
            new CommentNode(['id' => 5, 'parent_id' => 3, 'value' => '2nd level comment']),
            new CommentNode(['id' => 6, 'parent_id' => 5, 'value' => '3d level comment']),
            new CommentNode(['id' => 7, 'parent_id' => 5, 'value' => '3d level comment']),
            new CommentNode(['id' => 8, 'parent_id' => 5, 'value' => '3d level comment']),
        ];
    }

    /**
     * @param false $selfRef
     * @return GenealogyNode[]
     * @throws InvalidIdException
     */
    private function genealogyTestData($selfRef = false)
    {
        /*
          				5[3, 4]
                        /\
                       /  \
                      3    4[8, 6]
                     /\    /\
                    /  \  /  \
                   /    \/	  \
                  1    2  8    6[nil, 5] -> self ref
                 /\       /\
                /  \     /  \
               7   2    9    2
                       /\
                      /  \
                     x	  2
         */

        $data = [];

        $data[] = new GenealogyNode(['id' => 5, 'mother_id' => 3, 'father_id' => 4, 'name' => 'test 5']);
        $data[] = new GenealogyNode([
            'id' => 6, 'mother_id' => null, 'father_id' => $selfRef ? 5 : null, 'name' => 'test 6'
        ]);
        $data[] = new GenealogyNode(['id' => 3, 'mother_id' => 1, 'father_id' => 2, 'name' => 'test 3']);
        $data[] = new GenealogyNode(['id' => 1, 'mother_id' => 7, 'father_id' => 2, 'name' => 'test 1']);
        $data[] = new GenealogyNode(['id' => 7, 'mother_id' => null, 'father_id' => null, 'name' => 'test 7']);
        $data[] = new GenealogyNode([
            'id' => 2, 'mother_id' => null, 'father_id' => null, 'name' => 'test 2', 'trait_x' => 100
        ]);
        $data[] = new GenealogyNode(['id' => 4, 'mother_id' => 8, 'father_id' => 6, 'name' => 'test 4']);
        $data[] = new GenealogyNode(['id' => 8, 'mother_id' => 9, 'father_id' => 2, 'name' => 'test 8']);
        $data[] = new GenealogyNode(['id' => 9, 'mother_id' => null, 'father_id' => 2, 'name' => 'test 9']);

        return $data;
    }
}
