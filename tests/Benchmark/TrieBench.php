<?php

namespace RGilyov\Trees\Test\Benchmark;

use RGilyov\Trees\Test\DataTrait;
use RGilyov\Trees\Trie\Node;

/**
 * https://phpbench.readthedocs.io/en/latest/quick-start.html
 *
 * vendor/bin/phpbench run tests/Benchmark --report=default
 */
class TrieBench
{
    use DataTrait;

    /**
     * @var Node
     */
    private $trie;

    /**
     * @return void
     */
    public function __construct()
    {
        $ips = $this->trieTestData();

        $trie = new Node();

        foreach ($ips as $ip) {
            $trie->add(explode('.', $ip));
        }

        $this->trie = $trie;
    }

    /**
     * @Iterations(3)
     */
    public function benchTrieSearch()
    {
        $testIP1 = explode('.', '180.150.142.140');
        $testIP2 = explode('.', '150.146.137.139');
        $testIP3 = explode('.', '128.145.139.135');
        $ipNotExists = explode('.', '181.145.139.135');

        $this->trie->exists($testIP1);
        $this->trie->exists($testIP2);
        $this->trie->exists($testIP3);
        $this->trie->exists($ipNotExists);
    }

    /**
     * @Iterations(3)
     */
    public function benchLoopSearch()
    {
        $testIP1 = '180.150.142.140';
        $testIP2 = '150.146.137.139';
        $testIP3 = '128.145.139.135';
        $ipNotExists = '181.145.139.135';

        $this->arrSearch($testIP1);
        $this->arrSearch($testIP2);
        $this->arrSearch($testIP3);
        $this->arrSearch($ipNotExists);
    }

    /**
     * @param $ip
     * @return bool
     */
    private function arrSearch($ip) : bool
    {
        foreach (static::$ips as $v) {
            if ($ip === $v) {
                return true;
            }
        }

        return false;
    }
}
