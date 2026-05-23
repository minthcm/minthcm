
{if $fields.status.value != 'closed' && $bean->aclAccess("edit")}
    <input type="button" value="{$MOD.LBL_CLOSE_PLAN}" id="CloseButton" />
    <script type="text/javascript" src="modules/WorkSchedules/tpls/CloseButton.js"></script>
{/if}

