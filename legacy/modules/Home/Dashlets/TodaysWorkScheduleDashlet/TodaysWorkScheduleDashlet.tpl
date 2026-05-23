<link rel="stylesheet" type="text/css" href="modules/Home/Dashlets/TodaysWorkScheduleDashlet/TodaysWorkScheduleDashlet.css" />
<script type="text/javascript" src="{sugar_getjspath file='modules/Home/Dashlets/TodaysWorkScheduleDashlet/TWSDashlet.js'}"></script>
<input type="hidden" name='first_day_of_week' id="first_day_of_week" value='{$firstDayOfWeek}'>
<input type="hidden" name='current_user_is_admin' id="current_user_is_admin" value='{$current_user_is_admin}'>
<input type="hidden" name='current_user_id' id="current_user_id" value='{$current_user_id}'>
<input type="hidden" name='dashlet_id' id="dashlet_id" value='{$id}'>
<div align="center">
    <div class="TWSDashlet">
        {include file="modules/Home/Dashlets/TodaysWorkScheduleDashlet/Toolbar.tpl"}
        <table class="TWSList" style="width:100%;" border="0" cellspacing="0" cellpadding="0">
            <tfoot>
            <td colspan="6" class="TWSListFooter">
                {include file="modules/WorkSchedules/tpls/TimeTrackingPane.tpl"}
            </td>
            </tfoot>
        </table>
    </div>
</div>
<script type="text/javascript">
    {literal}
        (function () {
           var all = document.querySelectorAll( 'div.TWSDashlet' );
           new TWSDashlet( all[all.length - 1] );
        })();
    {/literal}
</script>
{literal}
<style>
.twsdashlet-loader {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #0001;
    z-index: 10;
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
}
</style>
{/literal}
