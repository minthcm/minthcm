
{if $fields.supervisor_acceptance.value == 'accepted' && $bean->aclAccess("edit") && !$bean->checkOwner()}
    <input type="button" value="{$MOD.LBL_UNDO_ACCEPTANCE_BTN}" id="UndoAcceptanceButton" onclick="undoWorkPlan()" />
    <script type="text/javascript" src="modules/WorkSchedules/tpls/UndoAcceptanceButton.js"></script>
{/if}

