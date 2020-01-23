<link rel="stylesheet" type="text/css" href="modules/WorkSchedules/tpls/DurationFieldEditView.css" />
<input id="duration_hours" name="duration_hours" type="number" min="0" value="{$fields.duration_hours.value}" novalidate />
<span class="dateFormat">{$MOD.LBL_HOURS_HOURS}</span>
<select id="duration_minutes" name="duration_minutes">
    <option value="0">0</option>
    <option value="5">5</option>
    <option value="10">10</option>
    <option value="15">15</option>
    <option value="20">20</option>
    <option value="25">25</option>
    <option value="30">30</option>
    <option value="35">35</option>
    <option value="40">40</option>
    <option value="45">45</option>
    <option value="50">50</option>
    <option value="55">55</option>
</select>
<script type="text/javascript">$( '#duration_minutes' ).val({$fields.duration_minutes.value} );</script>
<script type="text/javascript" src="modules/WorkSchedules/tpls/DurationFieldEditView.js"></script>
<span class="dateFormat">{$MOD.LBL_HOURS_MINUTES}</span>
