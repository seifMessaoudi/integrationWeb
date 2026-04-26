<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Forme;

class FormeTest extends TestCase
{
    public function testSurfaceRectangle()
    {
        $f = new Forme();
        $f->setLongueur(4);
        $f->setLargeur(3);
        $f->setType("rectangle");
        $this->assertSame(12, $f->surface());
    }

    public function testSurfaceCarre()
    {
        $f = new Forme();
        $f->setLongueur(5);
        $f->setLargeur(5);
        $f->setType("carre");
        $this->assertSame(25, $f->surface());
    }

    public function testCarreInvalideException()
    {
        $this->expectException(\Exception::class);
        $f = new Forme();
        $f->setLongueur(5);
        $f->setLargeur(3);
        $f->setType("carre");
        $f->surface();
    }
}