<?php

namespace RGilyov\Trees\Test;

use PHPUnit\Framework\TestCase;
use RGilyov\Trees\Comments\DuplicateElementsException;

class CommentsTest extends TestCase
{
    use DataTrait;

    /** @test */
    public function testTreeBuild()
    {
        $comments = $this->buildTestData();

        $commentsBuilder = new \RGilyov\Trees\Comments\CommentsBuilder();

        $root = $comments[0];

        $commentsBuilder->buildTree($root, $comments);

        $this->assertEquals('1st level comment', $root->getChildren()[1]['value']);
        $this->assertEquals('2nd level comment', $root->getChildren()[1]->getChildren()[1]['value']);

        $this->assertEquals(
            '3d level comment',
            $root->getChildren()[1]->getChildren()[1]->getChildren()[0]['value']
        );
    }

    /** @test */
    public function testRecursiveDataErrorTreeBuild()
    {
        $comments = $this->buildTestData();

        $comments[] = new \RGilyov\Trees\Comments\Comment(1, 1, ['value' => 'rec ref']);

        $commentsBuilder = new \RGilyov\Trees\Comments\CommentsBuilder();

        $root = $comments[0];

        $this->expectException(DuplicateElementsException::class);

        $commentsBuilder->buildTree($root, $comments);
    }
}