<?php

namespace RGilyov\Trees\Test;

use RGilyov\Trees\Comments\Comment;
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
     * @return \RGilyov\Trees\Comments\Comment[]
     * @throws InvalidIdException
     */
    private function commentsTestData()
    {
        return [
            new Comment(['id' => 1, 'parent_id' => null, 'value' => 'Root comment']),
            new Comment(['id' => 2, 'parent_id' => 1, 'value' => '1st level comment']),
            new Comment(['id' => 3, 'parent_id' => 1, 'value' => '1st level comment']),
            new Comment(['id' => 4, 'parent_id' => 3, 'value' => '2nd level comment']),
            new Comment(['id' => 5, 'parent_id' => 3, 'value' => '2nd level comment']),
            new Comment(['id' => 6, 'parent_id' => 5, 'value' => '3d level comment']),
            new Comment(['id' => 7, 'parent_id' => 5, 'value' => '3d level comment']),
            new Comment(['id' => 8, 'parent_id' => 5, 'value' => '3d level comment']),
        ];
    }
}
