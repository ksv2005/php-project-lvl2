<?php

namespace DifferenceCalculator\Parsers;

use Symfony\Component\Yaml\Yaml;

function getParser($data, $type)
{
    if ($type == 'json') {
        return json_decode($data, true);
    } elseif ($type == 'yml') {
        return Yaml::parse($data);
    } else {
        \Exception("Not support");
    }
}

function getData($pathToFile)
{
    $type = pathinfo($pathToFile)['extension'];
    $path = file_exists($pathToFile) ? $pathToFile : getcwd() . '/' . $pathToFile;
    $data = file_get_contents($path);
    return getParser($data, $type);
}
