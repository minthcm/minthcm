<?php
/* * *******************************************************************************
 * This file is part of KReporter. KReporter is an enhancement developed
 * by aac services k.s.. All rights are (c) 2016 by aac services k.s.
 *
 * This Version of the KReporter is licensed software and may only be used in
 * alignment with the License Agreement received with this Software.
 * This Software is copyrighted and may not be further distributed without
 * witten consent of aac services k.s.
 *
 * You can contact us at info@kreporter.org
 * ****************************************************************************** */




if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

if (!function_exists("randomstring")) {

    function randomstring()
    {
        $len = 10;
        $base = 'abcdefghjkmnpqrstwxyz';
        $max = strlen($base) - 1;
        $returnstring = '';
        //2013-09-06 BUG #496 removed ... causing issues in higher php releases
        //mt_srand((double)microtime()*1000000);
        while (strlen($returnstring) < $len + 1)
            $returnstring .= $base[mt_rand(0, $max)];

        return $returnstring;
    }
}

if (!function_exists("json_decode_kinamu")) {

    function json_decode_kinamu($json)
    {
        if (function_exists('json_decode')) return json_decode($json, true);

        // bugfix 2010-8-23: problem with json in AJAX call
        if ($json != '') {
            // Author: walidator.info 2009
            $comment = false;
            $out = '$x=';

            for ($i = 0; $i < strlen($json); $i++) {
                if (!$comment) {
                    if ($json[$i] == '{' or $json[$i] == '[') $out .= ' array(';
                    else if ($json[$i] == '}' or $json[$i] == ']') $out .= ')';
                    else if ($json[$i] == ':') $out .= '=>';
                    else $out .= $json[$i];
                } else $out .= $json[$i];
                if ($json[$i] == '"') $comment = !$comment;
            }
            eval($out.';');
            return $x;
        } else {
            return array();
        }
    }
}

if (!function_exists("jarray_encode_kinamu")) {

    function jarray_encode_kinamu($inArray)
    {
        if (!is_array($inArray)) return '';

        // so we have an array
        foreach ($inArray as $thisKey => $thisValue) {
            $resArray[] = "['".$thisKey."','".$thisValue."']";
        }
        return htmlentities('['.implode(',', $resArray).']', ENT_QUOTES);
    }
}
if (!function_exists("json_encode_kinamu")) {

    function json_encode_kinamu($input)
    {
        if (function_exists('json_encode')) return json_encode($input);
        else {
            $json = new Services_JSON();
            return $json->encode($input);
        }
    }
}


// since this was moved with 5.5.1
if (!function_exists('html_entity_decode_utf8')) {

    function html_entity_decode_utf8($string)
    {
        static $trans_tbl;
        // replace numeric entities
        $string = preg_replace('~&#x([0-9a-f]+);~ei', 'code2utf(hexdec("\\1"))', $string);
        $string = preg_replace('~&#([0-9]+);~e', 'code2utf(\\1)', $string);
        // replace literal entities
        if (!isset($trans_tbl)) {
            $trans_tbl = array();
            foreach (get_html_translation_table(HTML_ENTITIES) as $val => $key)
                $trans_tbl[$key] = utf8_encode($val);
        }
        return strtr($string, $trans_tbl);
    }
}

function calculate_trendline($values, $offset = true)
{
    // get the total
    $sumX = 0;
    $sumY = 0;
    foreach ($values as $datapointX => $datapointY) {
        $sumY += $datapointY;
        $sumX += $datapointX;
    }

    // get the averages
    $avgX = $sumX / count($values);
    $avgY = $sumY / count($values);

    // get the alpha
    $sumNalpha = 0;
    $sumZalpha = 0;
    foreach ($values as $datapointX => $datapointY) {
        $sumNalpha += ($datapointX - $avgX) * ($datapointY - $avgY);
        $sumZalpha += ($datapointX - $avgX) * ($datapointX - $avgX);
    }

    // calculate the alpha value
    $alpha = $sumZalpha > 0 ? $sumNalpha / $sumZalpha : 0;

    $startValue = $avgY - (((count($values) / 2) + 1) * $alpha);
    $endValue = $avgY + (((count($values) / 2) + 1) * $alpha);

    return array(
        'start' => round($startValue, 0),
        'end' => round($endValue, 0)
    );
}

function multisort($array, $sort_by, $key1, $key2 = NULL, $key3 = NULL, $key4 = NULL, $key5
    = NULL, $key6 = NULL)
{
    // sort by ?
    foreach ($array as $pos => $val)
        $tmp_array[$pos] = $val[$sort_by];
    asort($tmp_array);

    // display however you want
    foreach ($tmp_array as $pos => $val) {
        $return_array[$pos][$sort_by] = $array[$pos][$sort_by];
        $return_array[$pos][$key1] = $array[$pos][$key1];
        if (isset($key2)) {
            $return_array[$pos][$key2] = $array[$pos][$key2];
        }
        if (isset($key3)) {
            $return_array[$pos][$key3] = $array[$pos][$key3];
        }
        if (isset($key4)) {
            $return_array[$pos][$key4] = $array[$pos][$key4];
        }
        if (isset($key5)) {
            $return_array[$pos][$key5] = $array[$pos][$key5];
        }
        if (isset($key6)) {
            $return_array[$pos][$key6] = $array[$pos][$key6];
        }
    }
    return $return_array;
}

function sortFieldArrayBySequence($first, $second)
{
    return $first['sequence'] - $second['sequence'];
}

function getLastDayOfMonth($month, $year)
{
    return date('Y-m-d', strtotime('-1 second', strtotime('+1 month', strtotime($month.'/01/'.$year.' 00:00:00'))));
}
if (!function_exists("formatEnumArray")) {

    function formatEnumArray($a)
    {
        $parse_array = false;
        if (is_array($a)) {
            $first_element = array_slice($a, 0, 1, true);
            if (
                is_array($first_element)
                && !isset($first_element['value'])
                && !isset(array_values($a)[0]['value'])
            ) {
                $parse_array = true;
            }
        }
        if ($parse_array) {
            $new_array = array();
            foreach ($a as $value => $text) {
                $new_array [] = array(
                    'text' => $text,
                    'value' => $value,
                );
            }
            return $new_array;
        }
        return $a;
    }
}

if (file_exists('custom/modules/KReports/utils.php'))
        include('custom/modules/KReports/utils.php');
