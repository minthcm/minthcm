{assign var="yes" value=""}
{assign var="no" value=""}
{assign var="default" value=""}

{if strval({{sugarvar key='value' stringFormat='false'}}) == "1"}
    {assign var="yes" value="SELECTED"}
    {elseif strval({{sugarvar key='value' stringFormat='false'}}) == "0"}
        {assign var="no" value="SELECTED"}
    {else}
        {assign var="default" value="SELECTED"}
    {/if}

    <select id="{{sugarvar key='name'}}" name="{{sugarvar key='name'}}" {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}}  {{$displayParams.field}}>
        <option value="" {$default}></option>
        <option value = "0" {$no}> {$APP.LBL_SEARCH_DROPDOWN_NO}</option>
        <option value = "1" {$yes}> {$APP.LBL_SEARCH_DROPDOWN_YES}</option>
    </select>

