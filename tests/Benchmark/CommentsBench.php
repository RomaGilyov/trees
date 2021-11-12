<?php

namespace RGilyov\Trees\Test\Benchmark;

use RGilyov\Trees\Test\DataTrait;

class CommentsBench
{
    use DataTrait;

    public function benchTreeBuild()
    {
        $comments = $this->buildTestData();

        for ($i = 0; $i < 1000000; $i++) {
            $commentsBuilder = new \RGilyov\Trees\Comments\CommentsBuilder();

            $root = $comments[0];

            $commentsBuilder->buildTree($root, $comments);
        }
    }
}
