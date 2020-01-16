<?php

namespace DifferenceCalculator\Cli;

use Docopt;

use function DifferenceCalculator\Differ\genDiff;

function run()
{
    $doc = <<<'DOCOPT'
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: pretty]  

DOCOPT;

    $result = Docopt::handle($doc, [
        'version' => '0.1',
    ]);

    if (array_key_exists('--format', $result->args)) {
        $diff = genDiff($result->args['<firstFile>'], $result->args['<secondFile>']);
        print_r($diff);
    }
}
