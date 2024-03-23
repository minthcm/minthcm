{if strval({{sugarvar key='value' stringFormat='false'}}) == "1" || strval({{sugarvar key='value' stringFormat='false'}}) == "yes" || strval({{sugarvar key='value' stringFormat='false'}}) == "on"}
    {assign var="checked" value="CHECKED"}
{else}
    {assign var="checked" value=""}
{/if}

<!--<input type="checkbox" class="checkbox" name="{{sugarvar key='name'}}" id="{{sugarvar key='name'}}" value="{{sugarvar key='value' stringFormat='false'}}" disabled="true" {$checked}>-->

{if $checked=='CHECKED'}
    <input type="checkbox" class="checkbox" name="{{sugarvar key='name'}}" id="{{sugarvar key='name'}}" value="{{sugarvar key='value' stringFormat='false'}}" disabled="true" {$checked}>
{else}
    <span class="checkboxCustom styled red "><input type="checkboxCustom" name="{{sugarvar key='name'}}" id="{{sugarvar key='name'}}"  class="styled red" value="{{sugarvar key='value' stringFormat='false'}}" style="display: none;"></span>
    {/if}

{{if !empty($displayParams.enableConnectors)}}
{{sugarvar_connector view='DetailView'}}
{{/if}}