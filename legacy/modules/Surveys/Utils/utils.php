<?php
function survey_url_display(Surveys $survey)
{
    //MintHCM #74241 START
    //if ($survey->status != 'Public') {
    if ($survey->status != 'Active') {
        //MintHCM #74241 END
        return '';
    }
    global $sugar_config, $current_user;
    $url = $sugar_config['site_url'] . "/legacy/index.php?entryPoint=survey&id=" . $survey->id;
    $url_href = $url . '&employee=' . $current_user->id;
    return "<a href='$url_href'>$url</a>";
}
