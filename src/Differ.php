<?php

namespace DifferenceCalculator\Differ;

use function DifferenceCalculator\Parsers\getData;

function genDiff($pathToFile1, $pathToFile2)
{
    $differData = differ(getData($pathToFile1), getData($pathToFile2));
    return render($differData);
}

function getIndent($level)
{
    return str_repeat(" ", 4 * $level);
}

function render($diff, $level = 0)
{
    $root = implode("\n", array_map(function ($item) use ($level) {
        $level++;
        if (array_key_exists('children', $item)) {
            $key = $item['key'];
            $value = render($item['children'], $level);
            $indent = getIndent($level);
            return "{$indent}{$key}: {$value}";
        } else {
            return createElement($item, $level);
        }
    }, $diff));
    $indent = getIndent($level);
    return "{\n{$root}\n$indent}";
}

function createElement($item, $level)
{
    $indent = getIndent($level - 1);
    $type = $item['type'];
    $key = $item['key'];
    $before = $item['before'];
    $after = $item['after'];
    if ($type == 'edit') {
        $element[] = "{$indent}  + {$key}: " . checkArray($after, $level);
        $element[] = "{$indent}  - {$key}: " . checkArray($before, $level);
    }
    if ($type == 'delete') {
        $element[] = "{$indent}  - {$key}: " . checkArray($before, $level);
    }
    if ($type == 'add') {
        $element[] = "{$indent}  + {$key}: " . checkArray($after, $level);
    }
    if ($type == 'equal') {
        $element[] = "{$indent}    {$key}: " . checkArray($after, $level);
    }
    return implode("\n", $element);
}

function checkArray($item, $level)
{
    if (is_array($item)) {
        $element = createElement($item[0], $level);
        $indent = getIndent($level);
        return "{\n    {$element}\n$indent}";
    }
    return $item;
}

function differ($array1, $array2)
{
    $keys = array_keys(array_merge($array1, $array2));
    $result = array_map(function ($key) use (&$array1, &$array2) {
        if (
            array_key_exists($key, $array1) && array_key_exists($key, $array2) &&
            is_array($array1[$key]) && is_array($array2[$key])
        ) {
            return [
                'type' => 'tree',
                'key' => $key,
                'children' => differ($array1[$key], $array2[$key]),
                'before' => '',
                'after' => '',
            ];
        } elseif (!array_key_exists($key, $array1)) {
            return [
                'type' => 'add',
                'key' => $key,
                'before' => '',
                'after' => getLogicValue($array2[$key]),
            ];
        } elseif (!array_key_exists($key, $array2)) {
            return [
                'type' => 'delete',
                'key' => $key,
                'before' => getLogicValue($array1[$key]),
                'after' => '',
            ];
        } elseif ($array1[$key] != $array2[$key]) {
            return [
                'type' => 'edit',
                'key' => $key,
                'before' => getLogicValue($array1[$key]),
                'after' => getLogicValue($array2[$key]),
            ];
        } elseif ($array1[$key] == $array2[$key]) {
            return [
                'type' => 'equal',
                'key' => $key,
                'before' => getLogicValue($array1[$key]),
                'after' => getLogicValue($array2[$key]),
            ];
        }
    }, $keys);

    return $result;
}

function getLogicValue($value)
{
    if (is_array($value)) {
        return array_map(function ($key, $value) {
            return [
                'type' => 'equal',
                'key' => $key,
                'before' => $value,
                'after' => $value,
            ];
        }, array_keys($value), $value);
    }
    if ($value === true) {
        return 'true';
    }
    if ($value === false) {
        return 'false';
    }
    return $value;
}
