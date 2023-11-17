
{if $bean->type == 'for_reservation' && isset($reservations_calendar_list_access) && $reservations_calendar_list_access}
   <input type="button" value="{$MOD.LBL_SHOW_RESERVATION_CALENDAR_BTN}" id="ShowReservationCalendarButton" onclick="SUGAR.ajaxUI.loadContent('index.php?module=ReservationsCalendar&action=index&resource_id={$bean->id}');" />
{/if}

