<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use function DifferenceCalculator\Differ\genDiff;

class EngineTest extends TestCase
{
    public function testDiffJson()
    {
        $pathBefore = __DIR__ . "/fixtures/before.json";
        $pathAfter = __DIR__ . "/fixtures/after.json";
        $resultTrue = file_get_contents(__DIR__ . "/fixtures/result.true");
        $diff = genDiff($pathBefore, $pathAfter);
        $this->assertEquals($resultTrue, $diff);
    }

    public function testDiffYaml()
    {
        $pathBefore = __DIR__ . "/fixtures/before.yml";
        $pathAfter = __DIR__ . "/fixtures/after.yml";
        $resultTrue = file_get_contents(__DIR__ . "/fixtures/result.true");
        $diff = genDiff($pathBefore, $pathAfter);
        $this->assertEquals($resultTrue, $diff);
    }

//    public function testTreeJson()
//    {
//        $pathBefore = __DIR__ . "/fixtures/before_tree.json";
//        $pathAfter = __DIR__ . "/fixtures/after_tree.json";
//        $resultTrue = file_get_contents(__DIR__ . "/fixtures/result_tree.true");
//        $diff = genDiff($pathBefore, $pathAfter);
//        $this->assertEquals($resultTrue, $diff);
//    }
//
//    public function testTreeYaml()
//    {
//        $pathBefore = __DIR__ . "/fixtures/before_tree.yml";
//        $pathAfter = __DIR__ . "/fixtures/after_tree.yml";
//        $resultTrue = file_get_contents(__DIR__ . "/fixtures/result_tree.true");
//        $diff = genDiff($pathBefore, $pathAfter);
//        $this->assertEquals($resultTrue, $diff);
//    }

}
