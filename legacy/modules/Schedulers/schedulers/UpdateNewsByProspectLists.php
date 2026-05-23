<?php

$job_strings[] = 'updateNewsByProspectLists';

require_once 'modules/ProspectLists/UpdateNewsByProspectLists.php';

function updateNewsByProspectLists()
{
    $update_news = new UpdateNewsByProspectLists();
    $update_news->run();
    return true;
}
