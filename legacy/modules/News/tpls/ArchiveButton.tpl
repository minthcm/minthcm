
{if $fields.news_status.value == 'published' && $bean->aclAccess('edit') }
    <input type="button" value="{$MOD.LBL_ARCHIVE_BTN}" id="archiveButton" onclick="changeNewsStatus( 'archived' );" />
{/if}

