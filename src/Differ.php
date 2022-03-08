<?php

namespace Differ\Differ;


function genDiff($file1, $file2)
{
    $first_data = (array)json_decode(file_get_contents(pathinfo($file1)["basename"]));
    $second_data = (array)json_decode(file_get_contents(pathinfo($file2)["basename"]));

    return parse($first_data, $second_data);
}


function parse($firstData, $secondData)
{
    $resultChanges = [];

    foreach ($firstData as $key => $value) {
        if (!key_exists($key, $secondData) or $secondData[$key] !== $value) {
            $resultChanges[] = [$key, $value, "-"];
        } else {
            $resultChanges[] = [$key, $value, " "];
        }
    }

    $addedArray = array_diff($secondData, $firstData);
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
