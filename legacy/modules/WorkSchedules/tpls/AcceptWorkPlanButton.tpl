
{if $bean->canBeAccepted()}
    <input type="button" value="{$MOD.LBL_ACCEPT_WORK_PLAN_BTN}" id="AcceptWorkPlanButton" onclick="acceptWorkPlan()" />
    <script type="text/javascript" src="modules/WorkSchedules/tpls/Button.js"></script>
    <script type="text/javascript" src="modules/WorkSchedules/tpls/AcceptWorkPlanButton.js"></script>
{/if}

