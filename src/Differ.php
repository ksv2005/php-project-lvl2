<?php

namespace Differ\Differ;


function genDiff($file1, $file2)
{
    $file1 = __DIR__ . "/$file1";
    $file2 = __DIR__ . "/$file2";
    $first_data = (array)json_decode(file_get_contents("$file1"));
    $second_data = (array)json_decode(file_get_contents("$file2"));

    $resultChanges = [];

    foreach ($first_data as $key => $value) {
        if (!key_exists($key, $second_data) or $second_data[$key] !== $value) {
            $resultChanges[] = [$key, $value, "-"];
        } else {
            $resultChanges[] = [$key, $value, " "];
        }
    }

    $addedArray = array_diff($second_data, $first_data);
    foreach ($addedArray as $key => $value) {
        $resultChanges[] = [$key, $value, "+"];
    }

    usort($resultChanges, function ($a, $b) {
        if ($a[0] > $b[0]) {
            return 1;
        } else {
            return -1;
        }
    });

    $result = [];
    for ($i = 0; $i < count($resultChanges); $i++) {
        $key = $resultChanges[$i][0];
        $value = $resultChanges[$i][1];
        $sign = $resultChanges[$i][2];
        $result[] = "$sign $key: $value";
    }
    return $result;
}
