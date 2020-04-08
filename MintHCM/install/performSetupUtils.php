<?php

function installStatus($msg, $cmd = null, $overwrite = false, $before = '[ok]<br>')
{
    $fname = 'install/status.json';
    if (!$overwrite && file_exists($fname)) {
        $stat = json_decode(file_get_contents($fname));
        //$msg = json_encode($stat);
        $msg = $stat->message.$before.$msg;
    }
    file_put_contents($fname, json_encode(array(
        'message' => $msg,
        'command' => $cmd,
    )));
}
function init(){
    
}