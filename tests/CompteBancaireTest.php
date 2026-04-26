<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\CompteBancaire;

class CompteBancaireTest extends TestCase
{
    public function testRetirer()
    {
        $compte = new CompteBancaire("Ali", 100);
        $compte->retirer(30);
        $this->assertSame(70.0, $compte->getSolde());
    }

    public function testRetirerSoldeInsuffisant()
    {
        $this->expectException(\Exception::class);
        $compte = new CompteBancaire("Ali", 100);
        $compte->retirer(200);
    }
}