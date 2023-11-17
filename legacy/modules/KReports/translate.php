<?php

include 'modules/KReports/language/en_us.lang.php';

$en_us = $mod_strings;

include 'modules/KReports/language/de_de.lang.php';

$de_de = $mod_strings;
$out = array();

foreach($en_us as $label => $text){
    $out[$label] = $de_de[$label] ?: 'EN: ' . $text;
}

//function write_array_to_file( $the_name, $the_array, $the_file, $mode="w", $header='' )
write_array_to_file('mod_strings', $out, 'modules/KReports/language/de_de.new.lang.php');