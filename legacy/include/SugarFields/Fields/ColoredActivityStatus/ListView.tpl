{capture name=status assign=status}
    {sugar_fetch object=$parentFieldArray key=$col}
{/capture}

{capture name=key assign=key}{php}
        global $app_list_strings;
        $status=trim($this->get_template_vars('status'));

        $meetings_options_list=$app_list_strings['meeting_status_dom'];
        $call_options_list=$app_list_strings['call_status_dom'];

        $meeting_status=trim(array_search($status,$meetings_options_list));

        if(!empty($meeting_status)){
            echo $meeting_status;
        }else{
           echo trim(array_search($status,$call_options_list));
        }
    {/php}
{/capture}

<span style="{$style}"><b>{$status}</b></span>