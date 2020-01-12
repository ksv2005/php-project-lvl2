<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

require_once("src/Engine.php");

class EngineTest extends TestCase
{
    public function testGenDiff()
    {
        $pathBeforeJson = __DIR__ . "/fixtures/before.json";
        $pathAfterJson = __DIR__ . "/fixtures/after.json";
        $pathBeforeYaml = __DIR__ . "/fixtures/before.yml";
        $pathAfterYaml = __DIR__ . "/fixtures/after.yml";
        $resultTrue = file_get_contents(__DIR__ . "/fixtures/result.true");
        $diffJson = \Differ\genDiff($pathBeforeJson, $pathAfterJson);
        $diffYaml = \Differ\genDiff($pathBeforeYaml, $pathAfterYaml);
        $this->assertEquals($resultTrue, $diffJson);
        $this->assertEquals($resultTrue, $diffYaml);
    }
}
