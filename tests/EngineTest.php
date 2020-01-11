<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

require_once("src/Engine.php");

class EngineTest extends TestCase
{
    public function testName()
    {
        $this->assertTrue(\Differ\name());
    }
}