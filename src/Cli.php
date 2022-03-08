<?php

namespace Differ\Cli;

use Docopt;

use function Differ\Differ\genDiff;

function run()
{
    $doc = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: stylish]

DOC;

    $result = Docopt::handle($doc, [
        'version' => '0.1',
    ]);

    $arrayDiff = genDiff($result['<firstFile>'], $result['<secondFile>'], $result['--format']);
    print_r("{\n");
    foreach ($arrayDiff as $item) {
        print_r("    " . $item . "\n");
    }
    print_r("}");
}
