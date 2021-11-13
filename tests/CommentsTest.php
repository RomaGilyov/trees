<?php

namespace RGilyov\Trees\Test;

use PHPUnit\Framework\TestCase;
use RGilyov\Trees\Comments\Comment;
use RGilyov\Trees\Comments\CommentInterface;
use RGilyov\Trees\Comments\CommentsBuilder;
use RGilyov\Trees\Comments\DuplicateElementsException;
use RGilyov\Trees\Comments\InvalidIdException;

class CommentsTest extends TestCase
{
    use DataTrait;

    /** @test */
    public function testTreeBuild()
    {
        $comments = $this->commentsTestData();

        $commentsBuilder = new CommentsBuilder();

        $root = $comments[0];

        $commentsBuilder->buildTree($root, $comments);

        $this->assertEquals('1st level comment', $root->getChildren()[1]['value']);
        $this->assertEquals('2nd level comment', $root->getChildren()[1]->getChildren()[1]['value']);

        $this->assertEquals(
            '3d level comment',
            $root->getChildren()[1]->getChildren()[1]->getChildren()[0]['value']
        );

        // To Array

        $data = $root->toArray();

        $this->assertEquals('3d level comment', $data['children'][1]['children'][1]['children'][0]['value']);
    }

    public function testTreeBuildWithSort()
    {
        $comments = $this->commentsTestData();

        $commentsBuilder = new CommentsBuilder();

        $root = $comments[0];

        /*
         * Sort by id desc
         */
        $commentsBuilder->sort(function (CommentInterface $a, CommentInterface $b) {
            return $b->getId() <=> $a->getId();
        })->buildTree($root, $comments);

        $this->assertEquals(8, $root->getChildren()[0]->getChildren()[0]->getChildren()[0]->getId());
    }

    /** @test */
    public function testDuplicateDataErrorTreeBuild()
    {
        $comments = $this->commentsTestData();

        $comments[] = new Comment(['id' => 1, 'parent_id' => 2, 'value' => 'duplicate']);

        $commentsBuilder = new CommentsBuilder();

        $root = $comments[0];

        $this->expectException(DuplicateElementsException::class);

        $commentsBuilder->buildTree($root, $comments);
    }

    /** @test */
    public function testInvalidIdError()
    {
        $this->expectException(InvalidIdException::class);

        new Comment(['id' => null, 'parent_id' => 2, 'value' => 'duplicate']);

        $this->expectException(InvalidIdException::class);

        new Comment(['id' => 1, 'value' => 'duplicate']);
    }
}
