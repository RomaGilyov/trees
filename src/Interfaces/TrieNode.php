<?php

namespace RGilyov\Trees\Interfaces;

interface TrieNode extends Node
{
    /**
     * @param TrieNode $node
     * @return void
     */
    public function setChild(TrieNode $node);

    /**
     * @return TrieNode[]
     */
    public function getChildren() : array;
}
