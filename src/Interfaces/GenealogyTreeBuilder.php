<?php

namespace RGilyov\Trees\Interfaces;

interface GenealogyTreeBuilder
{
    /**
     * @param GenealogyNode $root
     * @param GenealogyNode[] $ancestors
     */
    public function buildTree(GenealogyNode $root, array $ancestors);
}
