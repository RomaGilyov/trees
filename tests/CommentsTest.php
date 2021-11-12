<?php

use PHPUnit\Framework\TestCase;

class CommentsTest extends TestCase
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
     * @var array[]
     */
    private $testData = [
        ['id' => 1, 'parent_id' => null, 'value' => 'Root comment'],
        ['id' => 2, 'parent_id' => 1, 'value' => '1st level comment'],
        ['id' => 3, 'parent_id' => 1, 'value' => '1st level comment'],
        ['id' => 4, 'parent_id' => 3, 'value' => '2nd level comment'],
        ['id' => 5, 'parent_id' => 3, 'value' => '2nd level comment'],
        ['id' => 6, 'parent_id' => 5, 'value' => '3d level comment'],
        ['id' => 7, 'parent_id' => 5, 'value' => '3d level comment'],
        ['id' => 8, 'parent_id' => 5, 'value' => '3d level comment'],
    ];

    /**
     * @return \RGilyov\Trees\Comments\CommentInterface[]
     */
    private function buildTestData()
    {
        $comments = [];

        foreach ($this->testData as $comment) {
            $comments[] = new \RGilyov\Trees\Comments\Comment($comment['id'], $comment['parent_id'], $comment);
        }

        return $comments;
    }

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

    public function testRecursiveDataErrorTreeBuild()
    {

    }
}
