<?php
namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;

class FactorielleTest extends TestCase
{
    public function calculFactoriel(int $mbr): int
    {
        $f = 1;
        for ($i = 2; $i <= $mbr; $i++) {
            $f *= $i;
        }
        return $f;
    }

    public static function tableauDonnees(): array
    {
        return [
            [3, 6],
            [7, 5040],
            [5, 120],
            [8, 40320],
        ];
    }

    /**
     * @dataProvider tableauDonnees
     */
    public function testCalcul(int $mbr, int $resultatAttendu): void
    {
        $resultatObtenu = $this->calculFactoriel($mbr);
        $this->assertEquals($resultatAttendu, $resultatObtenu);
    }
}