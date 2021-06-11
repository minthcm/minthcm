
{if $fields.reservation_type.value != 'true' && $bean->aclAccess("edit")}
    <input type="button" value="{$MOD.LBL_RESERVE}" id="ReservationButton" />
    <script type="text/javascript" src="modules/Rooms/tpls/ReservationButton.js"></script>
{/if}

