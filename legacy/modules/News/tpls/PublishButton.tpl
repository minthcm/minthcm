
{if $fields.news_status.value == 'draft' && $bean->aclAccess('edit') }
    <input type="button" value="{$MOD.LBL_PUBLISH_BTN}" id="publishButton" onclick="changeNewsStatus( 'published' );" />
{/if}

