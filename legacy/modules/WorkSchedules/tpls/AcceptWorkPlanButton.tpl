
{if $fields.supervisor_acceptance.value == 'wait' && $bean->aclAccess("edit") && !$bean->checkOwner()}
    <input type="button" value="{$MOD.LBL_ACCEPT_WORK_PLAN_BTN}" id="AcceptWorkPlanButton" onclick="acceptWorkPlan()" />
    <script type="text/javascript" src="modules/WorkSchedules/tpls/AcceptWorkPlanButton.js"></script>
{/if}

