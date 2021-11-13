<?php

namespace RGilyov\Trees\Interfaces;

interface GenealogyNode extends Node
{
    /**
     * @return int|string
     */
    public function getMotherId();

    /**
     * @return int|string
     */
    public function getFatherId();

    /**
     * @param GenealogyNode $node
     * @return void
     */
    public function setMother(GenealogyNode $node);

    /**
     * @param GenealogyNode $node
     * @return void
     */
    public function setFather(GenealogyNode $node);

    /**
     * @return GenealogyNode
     */
    public function getMother() : ?GenealogyNode;

    /**
     * @return GenealogyNode
     */
    public function getFather() : ?GenealogyNode;
}
