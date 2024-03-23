<select id="resources" style="width: 200px;">
    {foreach from=$resources item=resource}
        <option value="{$resource->id}"{if $resource->id == $default_resource} selected{/if}>{$resource->name}</option>
    {/foreach}
</select>

<script type="text/javascript">
    calendarList = {$calendars};
    reservationList = {$reservations};
    calendar_fdow = {if isset($CALENDAR_FDOW)}{$CALENDAR_FDOW|@intval}{else}1{/if};
</script>

<input style="margin-left: 20px;" type="button" class="button tuiCalendar" value="{$MOD.LNK_RESERVATION_CALENDAR_TODAY}" onclick="calendar.today();" />

<input style="margin-left: 20px;" type="button" class="button tuiCalendar" value="{$MOD.LNK_RESERVATION_CALENDAR_DAY}" onclick="calendar.changeView( 'day', true );" />
<input type="button" class="button tuiCalendar" value="{$MOD.LNK_RESERVATION_CALENDAR_WEEK}" onclick="calendar.changeView( 'week', true );" />
<input type="button" class="button tuiCalendar" value="{$MOD.LNK_RESERVATION_CALENDAR_MONTH}" onclick="calendar.changeView( 'month', true );" />


<input style="margin-left: 20px;" type="button" class="button tuiCalendar" value="<" onclick="calendar.prev();" />
<input type="button" class="button tuiCalendar" value=">" onclick="calendar.next();" />

<div style="display: inline; margin-left: 20px; font-weight: bold; font-size: 16px;" id="currentCalendarDate"></div>

<div id="calendar" style="height: 800px;"></div>

<link rel="stylesheet" type="text/css" href="include/tui-calendar/tui-calendar.css" />
<script type="text/javascript" src="include/tui-calendar/tui-code-snippet.js"></script>
<script type="text/javascript" src="include/tui-calendar/tui-calendar.js"></script>
<script type="text/javascript" src="modules/ReservationsCalendar/js/view.list.js"></script> 
