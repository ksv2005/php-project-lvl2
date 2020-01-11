<?php

namespace Differ;

use Docopt;
use Funct;

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

function genDiff($pathToFile1, $pathToFile2)
{
    $diff = diffArray(getFromJson($pathToFile1), getFromJson($pathToFile2));
    $result = implode(array_map(function ($item) {
            return " {$item['diff']} {$item['key']}: {$item['value']}\n";
    }, $diff));
    return "{\n{$result}}";
}

function createAcc($diff, $key, $value)
{
    if ($value === true) {
        $value = 'true';
    }
    if ($value === false) {
        $value = 'false';
    }
    return [
        'diff' => $diff,
        'key' => $key,
        'value' => $value,
    ];
}

function diffArray($array1, $array2)
{
    $keys = array_keys(array_merge($array1, $array2));
    $result = array_reduce($keys, function ($acc, $key) use ($array1, $array2) {
        if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
            if ($array1[$key] == $array2[$key]) {
                $acc[][] = createAcc(' ', $key, $array2[$key]);
            } else {
                $acc[] = [
                    createAcc('+', $key, $array2[$key]),
                    createAcc('-', $key, $array1[$key])
                ];
            }
        } elseif (array_key_exists($key, $array1)) {
            $acc[][] = createAcc('-', $key, $array1[$key]);
        } elseif (array_key_exists($key, $array2)) {
            $acc[][] = createAcc('+', $key, $array2[$key]);
        }

        return $acc;
    }, []);
    return array_merge(...$result);
}

function getFromJson($pathToFile)
{
    return json_decode(
        file_get_contents(file_exists($pathToFile) ? $pathToFile : getcwd() . '/' . $pathToFile),
        true
    );
}

function name()
{
    return True;
}