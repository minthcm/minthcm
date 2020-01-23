{if strval($parentFieldArray.$col) == "1" || strval($parentFieldArray.$col) == "yes" || strval($parentFieldArray.$col) == "on"}
    {assign var="checked" value="CHECKED"}
{else}
    {assign var="checked" value=""}
{/if}
{if $checked=='CHECKED'}
    <input type="checkbox" class="checkbox" disabled="true" {$checked}>
{else}
    <span class="checkboxCustom styled red"><input type="checkboxCustom" class="styled red" style="display: none;"></span>
{/if}