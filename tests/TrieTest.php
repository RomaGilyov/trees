<?php

namespace RGilyov\Trees\Test;

use PHPUnit\Framework\TestCase;
use RGilyov\Trees\Trie\Node;

class TrieTest extends TestCase
{
    use DataTrait;

    /** @test */
    public function testTrie()
    {
        $ips = $this->trieTestData();

        $trie = new Node();

        foreach ($ips as $ip) {
            $trie->add(explode('.', $ip));
        }

        $testIP1 = explode('.', '180.150.142.140');
        $testIP2 = explode('.', '150.146.137.139');
        $testIP3 = explode('.', '128.145.139.135');

        $this->assertTrue($trie->exists($testIP1));
        $this->assertTrue($trie->exists($testIP2));
        $this->assertTrue($trie->exists($testIP3));

        $trie->remove($testIP1);
        $trie->remove($testIP2);
        $trie->remove($testIP3);

        $this->assertFalse($trie->exists($testIP1));
        $this->assertFalse($trie->exists($testIP2));
        $this->assertFalse($trie->exists($testIP3));

        $prefix = explode('.', '128.128');

        $results = $trie->prefixSearch($prefix);

        $this->assertCount(529, $results);
        $this->assertEquals(128, $results[528][0]);
        $this->assertEquals(128, $results[528][1]);
        $this->assertEquals(150, $results[528][2]);
        $this->assertEquals(150, $results[528][3]);

        $trieArr = $trie->treeToArray();

        $this->assertCount(644848, $trieArr);
    }
}
