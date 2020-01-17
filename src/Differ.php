<?php

namespace DifferenceCalculator\Differ;

use function DifferenceCalculator\Parsers\getData;

function genDiff($pathToFile1, $pathToFile2)
{
    $differData = differ(getData($pathToFile1), getData($pathToFile2));
    $renderData = render($differData);
    return "{\n{$renderData}}";
}

function render($diff)
{
    return implode(array_map(function ($item) {
        return "  {$item['diff']} {$item['key']}: {$item['value']}\n";
    }, $diff));
}

function differ($array1, $array2)
{
    $keys = array_keys(array_merge($array1, $array2));
    $result = array_reduce($keys, function ($acc, $key) use ($array1, $array2) {
        if (array_key_exists($key, $array1) && array_key_exists($key, $array2)) {
            if ($array1[$key] == $array2[$key]) {
                $acc[][] = createItem(' ', $key, $array2[$key]);
            } else {
                $acc[] = [
                    createItem('+', $key, $array2[$key]),
                    createItem('-', $key, $array1[$key])
                ];
            }
        } elseif (array_key_exists($key, $array1)) {
            $acc[][] = createItem('-', $key, $array1[$key]);
        } elseif (array_key_exists($key, $array2)) {
            $acc[][] = createItem('+', $key, $array2[$key]);
        }

        return $acc;
    }, []);
    return array_merge(...$result);
}

function createItem($diff, $key, $value)
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
