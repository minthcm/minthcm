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

function setMintInstallStatus($step, $message){
    $statusFile = __DIR__ . '/../../install/assets/status.json';

    $statusData = [];

    if(file_exists($statusFile)){
        $existingData = json_decode(file_get_contents($statusFile), true);

        if (is_array($existingData)) {
            $statusData = $existingData;
        }
    }

    $statusData[$step] = $message;

    $encodedStatusData = json_encode($statusData, JSON_PRETTY_PRINT);

    if (file_put_contents($statusFile, $encodedStatusData)) {
        echo "Status updated: $step - $message";
    } else {
        echo "Failed to update status.";
    }
}

function init(){
    
}