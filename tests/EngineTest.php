<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

require_once("src/Engine.php");

class EngineTest extends TestCase
{
    public function testGenDiff()
    {
        $pathBefore = __DIR__ . "/fixtures/before.json";
        $pathAfter = __DIR__ . "/fixtures/after.json";
        $resultTrue = file_get_contents(__DIR__ . "/fixtures/result.true.json");
        $resultFalse = file_get_contents(__DIR__ . "/fixtures/result.false.json");

        $diff = \Differ\genDiff($pathBefore, $pathAfter);

        $this->assertEquals($resultTrue, $diff);
        $this->assertNotEquals($resultFalse, $diff);
    }
}
