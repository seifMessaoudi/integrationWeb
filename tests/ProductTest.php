<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;

class ProductTest extends TestCase
{
    public function testFood()
    {
        $p = new Product("product", "food", 10);
        $tva = $p->computeTVA();
        $this->assertSame(0.55, $tva);
    }
}