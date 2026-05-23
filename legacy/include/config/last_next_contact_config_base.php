<?php

$last_next_modules = array('Candidates');
$last_next_modules_panels = $last_next_modules;
$related_modules = array('Candidates');
$last_next_activities_std_modules = array('Calls', 'Meetings', 'Emails');
$relationship_map = array(
    'Candidates' => 'candidates',
    'Users' => 'users',
);

$last_next_trigger_fields = array(
    'Calls' => array(
        'status',
        'date_start',
        'date_end',
        'parent_id',
    ),
    'Meetings' => array(
        'status',
        'date_start',
        'date_end',
        'parent_id',
    ),
    'Emails' => array(
        'status',
        'parent_id',
        //'date_sent' - przeniesione do configa
    ),
);
$only_owner_activities_enabled_config = [
    'Calls' => [
        'assigned_user_id',
    ],
    'Meetings' => [
        'assigned_user_id',
    ],
    'Emails' => [
        'assigned_user_id',
    ],
];

$job_execution_delay = 2; // po ilu minutach ma uruchamiać się aktualizacja dat ostatniego i planowanego kontaktu (po aktualizacji rekordu)
$process_records_limit = 50; // wielkość batcha do przeprocesowania z LastNextContactsQueue
