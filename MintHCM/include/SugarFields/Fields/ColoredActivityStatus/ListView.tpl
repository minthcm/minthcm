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

{if $key == 'Planned'}
    <span style="color:#6f6f6f;border:solid 1px;padding:5px 12px;border-radius:7px;border-color:#84d2e4;background:#f5fcff;white-space:nowrap"><b>{$status}</b></span>
{elseif $key == 'Held'}
    <span style="color:#6f6f6f;border:solid 1px;padding:5px 12px;border-radius:7px;border-color:#afedad;background:#f5fff5;white-space:nowrap"><b>{$status}</b></span>
{else}
    <span style="color:#6f6f6f;border:solid 1px;padding:5px 12px;border-radius:7px;border-color:#ed8083;background:#fff5f5;white-space:nowrap"><b>{$status}</b></span>
{/if}