
<div class="TWSToolbar">
   <script type="text/javascript" src="{sugar_getjspath file='modules/WorkSchedules/tpls/TimeTrackingPane.js'}"></script>
   <form onsubmit="return false" class="TWSToolbarForm">
      <table border="0" cellpadding="5" style="width:100%;">
         <tr>
            <td nowrap="nowrap">
               <span class="dateTime">
                  <input class="date_input" autocomplete="off" type="text" size="11" maxlength="10">
                  <img src="themes/SuiteP/images/jscalendar.gif" alt="{$APP.twsdashlet_insert_date}" style="position:relative; top:4px" border="0">
               </span>
            </td>
            <td nowrap="nowrap"><select style="min-width:300px;"></select></td>
            <td nowrap="nowrap">
               <img class="showPlanButton" src="themes/SuiteP/images/twsdashlet_show_24.png" alt="{$APP.twsdashlet_show}" title="{$APP.twsdashlet_show}" style="width:24px;height:24px;cursor:pointer;" border="0">
            </td>
            <td nowrap="nowrap">
               <img class="editPlanButton" src="themes/SuiteP/images/twsdashlet_edit_24.png" alt="{$APP.twsdashlet_edit}" title="{$APP.twsdashlet_edit}" style="width:24px;height:24px;cursor:pointer;" border="0">
            </td>
            <td nowrap="nowrap">
               {if $fields.status.value != 'closed'}
                  <img id="CloseButton" src="themes/SuiteP/images/twsdashlet_close_24.png" alt="{$MOD.LBL_CLOSE_PLAN}" title="{$MOD.LBL_CLOSE_PLAN}" style="width:24px;height:24px;cursor:pointer;" border="0">
                  <script type="text/javascript" src="{sugar_getjspath file='modules/WorkSchedules/tpls/CloseButton.js'}"></script>
               {/if}
            </td>
            <td nowrap="nowrap">
               <img class="logTimeButton" src="themes/SuiteP/images/twsdashlet_add_time_24.png" alt="{$APP.twsdashlet_add_time}" title="{$APP.twsdashlet_add_time}" style="width:24px;height:24px;cursor:pointer;" border="0">
            </td>
         </tr>
      </table>
   </form>
   <script type="text/javascript" src="{sugar_getjspath file='modules/Home/Dashlets/TodaysWorkScheduleDashlet/Toolbar.js'}"></script>
</div>
