<?php

namespace DifferenceCalculator\Differ;

use function DifferenceCalculator\Parsers\getData;

function genDiff($pathToFile1, $pathToFile2)
{
    $diff = differ(getData($pathToFile1), getData($pathToFile2));
    $result = implode(array_map(function ($item) {
            return "  {$item['diff']} {$item['key']}: {$item['value']}\n";
    }, $diff));
    return "{\n{$result}}";
}

function differ($array1, $array2)
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
