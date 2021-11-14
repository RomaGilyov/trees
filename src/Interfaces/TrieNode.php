<?php

namespace RGilyov\Trees\Interfaces;

interface TrieNode extends Node
{
    /**
     * @param array $values
     * @return void
     */
    public function add(array $values);

    /**
     * @param array $values
     * @return mixed
     */
    public function exists(array $values) : bool;

    /**
     * @param array $values
     * @return void
     */
    public function remove(array $values) : bool;

    /**
     * @return TrieNode[]
     */
    public function getChildren() : array;
}
