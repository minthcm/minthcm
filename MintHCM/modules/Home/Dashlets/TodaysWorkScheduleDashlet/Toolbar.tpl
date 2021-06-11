<div class="TWSToolbar">
    <script type="text/javascript" src="{sugar_getjspath file='modules/WorkSchedules/tpls/TimeTrackingPane.js'}"></script>
    <form onsubmit="return false" class="TWSToolbarForm">
        <table border="0" cellpadding="5" style="width:100%;">
            <tr>
                <td nowrap="nowrap" style="padding-right:30px;">
                    <span class="dateTime">
                        <div class="monthHeader" style="display:inline">
                            <a href="#" class="calendar_before">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 10 32" style="enable-background:new 0 0 32 32;" xml:space="preserve">
                                    <g id="layer1" transform="translate(0,-1036.3622)">
                                        <g id="g3126" style="fill:#ffffff" transform="matrix(0.82573651,0,0,0.82573651,-1.6833344,189.65762)">
                                            <rect transform="matrix(0.72609461,0.6875948,-0.71445905,0.69967726,0,0)" ry="0" rx="0" y="744.83148" x="751.50134" height="11.990089" width="3.0727561" id="rect3120" style="fill:#ffffff;fill-opacity:1;stroke:none"></rect>
                                            <rect style="fill:#ffffff;fill-opacity:1;stroke:none" id="rect3124" width="3.0727561" height="11.990089" x="752.80646" y="752.44446" rx="0" ry="0" transform="matrix(-0.71538329,0.69873224,0.72518508,0.68855399,0,0)"></rect>
                                        </g>
                                    </g>

                                </svg>
                            </a>
                        </div>
                        <input class="date_input" autocomplete="off" type="text" size="11" maxlength="10">
                        <div class="monthHeader" style="display:inline">
                            <a class="calendar_next" href="#">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve">
                                    <g id="layer1" transform="translate(0,-1036.3622)">
                                        <g id="g3126" style="fill:#ffffff" transform="matrix(-0.82573651,0,0,0.82573651,17.683335,189.65762)">
                                            <rect transform="matrix(0.72609461,0.6875948,-0.71445905,0.69967726,0,0)" ry="0" rx="0" y="744.83148" x="751.50134" height="11.990089" width="3.0727561" id="rect3120" style="fill:#ffffff;fill-opacity:1;stroke:none"></rect>
                                            <rect style="fill:#ffffff;fill-opacity:1;stroke:none" id="rect3124" width="3.0727561" height="11.990089" x="752.80646" y="752.44446" rx="0" ry="0" transform="matrix(-0.71538329,0.69873224,0.72518508,0.68855399,0,0)"></rect>
                                        </g>
                                    </g>

                                </svg></a>
                        </div>
                    </span>
                </td>
                 <td nowrap="nowrap">
                     <img class="calendar_img" src="themes/SuiteP/images/jscalendar.gif" alt="{$APP.twsdashlet_insert_date}" style="position:relative;margin-right:15px;margin-left:-15px;cursor:pointer;" border="0">
                </td>
                <td nowrap="nowrap" style="padding-left:20px;"><select style="min-width:300px;"></select></td>
                <td nowrap="nowrap">
                    <img class="showPlanButton" src="themes/SuiteP/images/twsdashlet_show_24.png" alt="{$APP.twsdashlet_show}" title="{$APP.twsdashlet_show}" style="width:24px;height:24px;cursor:pointer;" border="0">
                </td>
                <td nowrap="nowrap">
                    <img class="editPlanButton" src="themes/SuiteP/images/twsdashlet_edit_24.png" alt="{$APP.twsdashlet_edit}" title="{$APP.twsdashlet_edit}" style="width:24px;height:24px;cursor:pointer;" border="0">
                </td>
                <td nowrap="nowrap">
                    <img class="logTimeButton" src="themes/SuiteP/images/twsdashlet_add_time_24.png" alt="{$APP.twsdashlet_add_time}" title="{$APP.twsdashlet_add_time}" style="width:24px;height:24px;cursor:pointer;" border="0">
                </td>
                <td nowrap="nowrap">
                    {if $fields.status.value != 'closed'}
                        <img id="CloseButton" src="themes/SuiteP/images/twsdashlet_close_24.png" alt="{$MOD.LBL_CLOSE_PLAN}" title="{$MOD.LBL_CLOSE_PLAN}" style="width:24px;height:24px;cursor:pointer;" border="0">
                        <script type="text/javascript" src="{sugar_getjspath file='modules/WorkSchedules/tpls/CloseButton.js'}"></script>
                    {/if}
                </td>
            </tr>
        </table>
    </form>
    <script type="text/javascript" src="{sugar_getjspath file='modules/Home/Dashlets/TodaysWorkScheduleDashlet/Toolbar.js'}"></script>
</div>