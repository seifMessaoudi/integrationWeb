<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Personne;

class PersonneTest extends TestCase
{
    public function testMineur()
    {
        $p = new Personne();
        $p->setNom("Ali");
        $p->setPrenom("Ahmed");
        $p->setAge(15);
        $this->assertSame("mineur", $p->categorie());
    }

    public function testMajeur()
    {
        $p = new Personne();
        $p->setAge(20);
        $this->assertSame("majeur", $p->categorie());
    }

    public function testAgeInvalide()
    {
        $this->expectException(\Exception::class);
        $p = new Personne();
        $p->setAge(-2);
        $p->categorie();
    }
}