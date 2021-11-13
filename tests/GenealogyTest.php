<?php

namespace RGilyov\Trees\Test;

use PHPUnit\Framework\TestCase;
use RGilyov\Trees\Exceptions\InfiniteRecursionException;
use RGilyov\Trees\Genealogy\TreeBuilder;
use RGilyov\Trees\Interfaces\GenealogyNode;

class GenealogyTest extends TestCase
{
    use DataTrait;

    /** @test */
    public function testTreeBuild()
    {
        $data = $this->genealogyTestData();

        $root = $data[0];

        $builder = new TreeBuilder();

        $builder->buildTree($root, $data);

        $this->assertEquals(1, $root->getMother()->getMother()->getId());
        $this->assertEquals(7, $root->getMother()->getMother()->getMother()->getId());
        $this->assertEquals(2, $root->getFather()->getMother()->getMother()->getFather()->getId());
    }

    /** @test */
    public function testInfiniteRecursionError()
    {
        $this->expectException(InfiniteRecursionException::class);

        $data = $this->genealogyTestData(true);

        $root = $data[0];

        $builder = new TreeBuilder();

        $builder->buildTree($root, $data);
    }

    /** @test */
    public function testTraverse()
    {
        $data = $this->genealogyTestData();

        $root = $data[0];

        $builder = new TreeBuilder();

        $builder->buildTree($root, $data);

        $ids = [];

        $root->traverse(function (GenealogyNode $node) use (&$ids) {
            $ids[] = $node->getId();

            $node->changed = 1;
        });

        $this->assertCount(12, $ids);
        $this->assertEquals(1, $root->changed);
        $this->assertEquals(1, $root->getMother()->changed);
    }

    /** @test */
    public function testReverseTraverse()
    {
        $data = $this->genealogyTestData();

        $root = $data[0];

        $builder = new TreeBuilder();

        $builder->buildTree($root, $data);

        $root->reverseTraverse(function (GenealogyNode $node) {
            $mother = $node->getMother();
            $father = $node->getFather();

            if (is_null($father) && ! is_null($mother)) {
                $node->trait_x = ($mother->trait_x)/2;
            }

            if (! is_null($father) && is_null($mother)) {
                $node->trait_x = ($father->trait_x)/2;
            }

            if (! is_null($father) && ! is_null($mother)) {
                $node->trait_x = ($father->trait_x + $mother->trait_x)/2;
            }
        });

        $this->assertEquals(56.25, $root->trait_x);
        $this->assertEquals(37.5, $root->getFather()->trait_x);
        $this->assertEquals(75, $root->getFather()->getMother()->trait_x);
        $this->assertEquals(50, $root->getFather()->getMother()->getMother()->trait_x);
        $this->assertEquals(100, $root->getFather()->getMother()->getMother()->getFather()->trait_x);
    }

    public function testTreeToArray()
    {
        $data = $this->genealogyTestData();

        $root = $data[0];

        $builder = new TreeBuilder();

        $builder->buildTree($root, $data);

        $ar = $root->treeToArray();

        $this->assertEquals(1, $ar['mother']['mother']['id']);
        $this->assertEquals(7, $ar['mother']['mother']['mother']['id']);
        $this->assertEquals(2, $ar['father']['mother']['mother']['father']['id']);
    }
}
