<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

    $db = DBManagerFactory::getInstance();

    $even_id = $db->quote($_GET['event'] ?? '');
    $delegate_id =$db->quote( $_GET['delegate'] ?? '');
    $type = $db->quote($_GET['type'] ?? '');
    $response = $db->quote($_GET['response'] ?? '');

    //get event
    $event = BeanFactory::newBean('FP_events');
    $event->retrieve($even_id);
    
    $type_config = [
        'c'  => [
            'relationship'  => 'fp_events_contacts',
            'table'         => 'fp_events_contacts_c',
            'event_col'     => 'fp_events_contactsfp_events_ida',
            'delegate_col'  => 'fp_events_contactscontacts_idb',
        ],
        't'  => [
            'relationship'  => 'fp_events_prospects_1',
            'table'         => 'fp_events_prospects_1_c',
            'event_col'     => 'fp_events_prospects_1fp_events_ida',
            'delegate_col'  => 'fp_events_prospects_1prospects_idb',
        ],
        'ca' => [
            'relationship'  => 'fp_events_candidates',
            'table'         => 'fp_events_candidates',
            'event_col'     => 'fp_events_id',
            'delegate_col'  => 'candidates_id',
        ],
    ];

    if (isset($type_config[$type]) && in_array($response, ['accept', 'decline'])) {
        $config = $type_config[$type];
        $event->load_relationship($config['relationship']);

        $accept_status  = $response === 'accept' ? 'Accepted' : 'Declined';
        $redirect_field = $response === 'accept' ? 'accept_redirect' : 'decline_redirect';
        $echo_msg       = $response === 'accept' ? 'Thank you for accepting' : 'Thank you for declining';

        $check_q = 'SELECT email_responded FROM ' . $config['table']
            . ' WHERE ' . $config['event_col'] . '="' . $event->id . '"'
            . ' AND ' . $config['delegate_col'] . '="' . $delegate_id . '"';
        $check = $db->getOne($check_q);

        $query = 'UPDATE ' . $config['table']
            . ' SET accept_status="' . $accept_status . '", email_responded="1"'
            . ' WHERE ' . $config['event_col'] . '="' . $event->id . '"'
            . ' AND ' . $config['delegate_col'] . '="' . $delegate_id . '"'
            . ' AND email_responded="0"';

        if ($db->query($query) && $check != '1') {
            if (!IsNullOrEmptyString($event->$redirect_field)) {
                header('Location: ' . $event->$redirect_field);
            } else {
                echo $echo_msg;
            }
        } else {
            echo 'You have already responded to the invitation or there was a problem with the link. Please contact the sender of the invite for help.';
        }
    }
    // Function for basic field validation (present and neither empty nor only white space nor just 'http://')
    function IsNullOrEmptyString($question)
    {
        return (!isset($question) || trim($question)==='' || $question =='http://');
    }
